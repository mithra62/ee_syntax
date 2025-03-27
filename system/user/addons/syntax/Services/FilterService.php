<?php

namespace Mithra62\Syntax\Services;

use GeSHi;

class FilterService
{
    /**
     * @var string
     */
    protected string $syntax_token = '';

    /**
     * @var array
     */
    protected array $syntax_matches = [];

    /**
     * @var GeSHi
     */
    protected GeSHi $geshi;

    public function __construct()
    {
        $this->syntax_token = md5(uniqid(rand()));
        $this->syntax_matches = [];
    }

    /**
     * @param string $data
     * @return string
     */
    public function parse(string $data): string
    {
        $data = preg_replace_callback(
            "/\s*<pre(?:lang=[\"']([\w-]+)[\"']|line=[\"'](\d*)[\"']|escaped=[\"'](true|false)?[\"']|highlight=[\"']((?:\d+[,-])*\d+)[\"']|\s)+>(.*)<\/pre>\s*/siU",
            [$this, 'syntaxSubstitute'],
            $data
        );

        $data = preg_replace_callback(
            "/<p>\s*" . $this->syntax_token . "(\d{3})\s*<\/p>/si",
            [$this, 'syntaxHighlight'],
            $data
        );

        return $data;
    }

    /**
     * @param array $match
     * @return string
     */
    protected function syntaxHighlight(array $match): string
    {
        $i = intval($match[1]);
        $match = $this->syntax_matches[$i];
        $language = strtolower(trim($match[1]));
        $line = trim($match[2]);
        $escaped = trim($match[3]);

        $code = $this->codeTrim($match[5]);
        if ($escaped != "false") {
            $code = htmlspecialchars_decode($code);
        }

        $run_language = $language;
        if ($language == "ee") {
            //$run_language = 'html';
        }
        //$code = htmlspecialchars_decode($code);
        $this->geshi = new GeSHi($code, $run_language);

        $this->geshi->enable_keyword_links(false);

        $highlight = [];
        if (!empty($match[4])) {
            $highlight = !strpos($match[4], ',') ? [$match[4]] : explode(',', $match[4]);

            $h_lines = [];
            $total = sizeof($highlight);
            for ($i = 0; $i < $total; $i++) {
                $h_range = explode('-', $highlight[$i]);

                if (sizeof($h_range) == 2) {
                    $h_lines = array_merge($h_lines, range($h_range[0], $h_range[1]));
                } else {
                    $h_lines[] = $highlight[$i];
                }
            }

            $this->geshi->highlight_lines_extra($h_lines);
        }
        //END LINE HIGHLIGHT SUPPORT

        $output = "\n<div class=\"ee_syntax\">";
        $code = $this->geshi->parse_code();
        if ($language == "ee") {
            $code = html_entity_decode($code);
        }

        if ($line) {
            $output .= "<table><tr><td class=\"line_numbers\">";
            $output .= $this->lineNumbers($code, $line);
            $output .= "</td><td class=\"code\">";
            $output .= $code;
            $output .= "</td></tr></table>";
        } else {
            $output .= "<div class=\"code\">";
            $output .= $code;
            $output .= "</div>";
        }

        $output .= "</div>\n";

        return $output;
    }

    /**
     * @param string $code
     * @param string $start
     * @return string
     */
    protected function lineNumbers(string $code, string $start): string
    {
        $line_count = count(explode("\n", $code));
        $output = "<pre>";
        for ($i = 0; $i < $line_count; $i++) {
            $output .= ($start + $i) . "\n";
        }
        $output .= "</pre>";
        return $output;
    }

    /**
     * @param array $match
     * @return string
     */
    protected function syntaxSubstitute(array $match): string
    {
        $i = count($this->syntax_matches);
        $this->syntax_matches[$i] = $match;
        return "\n\n<p>" . $this->syntax_token . sprintf("%03d", $i) . "</p>\n\n";
    }

    /**
     * @param $code
     * @return string
     */
    protected function codeTrim($code): string
    {
        // special ltrim b/c leading whitespace matters on 1st line of content
        $code = preg_replace("/^\s*\n/siU", "", $code);
        $code = rtrim($code);
        return $code;
    }
}