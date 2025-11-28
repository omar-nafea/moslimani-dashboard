<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Settings
  |--------------------------------------------------------------------------
  */
  'show_warnings' => false,

  'public_path' => null,

  'convert_entities' => true,

  'options' => [
    /**
     * The location of the DOMPDF font directory
     */
    'font_dir' => storage_path('fonts'),

    /**
     * The location of the DOMPDF font cache directory
     */
    'font_cache' => storage_path('fonts'),

    /**
     * The location of a temporary directory.
     */
    'temp_dir' => sys_get_temp_dir(),

    /**
     * dompdf's "chroot" - Prevents dompdf from accessing system files
     */
    'chroot' => realpath(base_path()),

    /**
     * Protocol whitelist
     */
    'allowed_protocols' => [
      'file://' => ['rules' => []],
      'http://' => ['rules' => []],
      'https://' => ['rules' => []],
    ],

    /**
     * Whether to enable font subsetting or not.
     */
    'enable_font_subsetting' => true,

    /**
     * Whether to enable unicode.
     */
    'enable_unicode' => true,

    /**
     * The PDF rendering backend to use
     * Valid settings are 'PDFLib', 'CPDF' (default), 'GD', and 'auto'.
     */
    'pdf_backend' => 'CPDF',

    /**
     * html target media view which should be rendered into pdf.
     */
    'default_media_type' => 'screen',

    /**
     * The default paper size.
     */
    'default_paper_size' => 'a4',

    /**
     * The default paper orientation.
     */
    'default_paper_orientation' => 'portrait',

    /**
     * The default font family
     */
    'default_font' => 'dejavu sans',

    /**
     * Image DPI setting
     */
    'dpi' => 96,

    /**
     * Enable inline PHP
     */
    'enable_php' => false,

    /**
     * Enable inline Javascript
     */
    'enable_javascript' => true,

    /**
     * Enable remote file access
     */
    'enable_remote' => true,

    /**
     * Enable HTML5 parser
     */
    'enable_html5_parser' => true,

    /**
     * A ratio applied to DOMPDF_FONT_HEIGHT_RATIO
     */
    'font_height_ratio' => 1.1,

    /**
     * Use the more-than-experimental HTML5 Lib parser
     */
    'isPhpEnabled' => false,

    /**
     * Render HTML as XML
     */
    'isHtml5ParserEnabled' => true,

    /**
     * Debug PNG
     */
    'debugPng' => false,

    /**
     * Debug keep temp
     */
    'debugKeepTemp' => false,

    /**
     * Debug CSS
     */
    'debugCss' => false,

    /**
     * Debug layout
     */
    'debugLayout' => false,

    /**
     * Debug layout lines
     */
    'debugLayoutLines' => false,

    /**
     * Debug layout blocks
     */
    'debugLayoutBlocks' => false,

    /**
     * Debug layout inline
     */
    'debugLayoutInline' => false,

    /**
     * Debug layout padding box
     */
    'debugLayoutPaddingBox' => false,
  ],

];



