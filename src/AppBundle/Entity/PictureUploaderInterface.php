<?php

namespace AppBundle\Entity;


interface PictureUploaderInterface
{
    public function getPicture();
    public function setPicture();
    public function upload();
}