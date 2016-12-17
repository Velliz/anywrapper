<?php
/**
 * anywhere wrapper
 *
 * Class Wrapper and library for used with OAAS Anywhere
 *
 * Copyright (c) 2016, Didit Velliz
 *
 * @package    anywrapper
 * @author    Didit Velliz
 * @link    https://github.com/velliz/pukoframework
 * @since    Version 0.0.1
 *
 */
namespace anywrapper;

/**
 * Class AnywhereImage
 * @package anywrapper
 */
class AnywhereImage extends Wrapper
{
    protected function Init()
    {
        $this->mode = Wrapper::IMAGES;
    }

    //TODO: Image Wrapper
    public function Send($apiUrl)
    {

    }
}