<?php

namespace NeeZiaa\Twig\Extensions;

use NeeZiaa\Form\Form;
use Twig\Extension\AbstractExtension;

class FormExtension extends AbstractExtension {

    public Form $form;

    public function form(): Form
    {
        return $this->form = new Form();
    }

    public function input(): Form
    {
        return $this->form->input();
    }

    public function select(): Form
    {
        return $this->form->select();
    }

    public function option(): Form
    {
        return $this->form->option();
    }

    public function endselect(): Form
    {
        return $this->form->endselect();
    }

    public function button(): Form
    {
        return $this->form->button();
    }

    public function submit(): Form
    {
        return $this->form->submit();
    }

}