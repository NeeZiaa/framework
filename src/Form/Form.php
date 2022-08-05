<?php
namespace NeeZiaa\Form;

class Form{

    private array $data; // element values
    private array $class; // classes common to all elements
    private array $options_select = [];

    /**
     * @param array $data element values
     * @param array $class classes common to all elements
     */

    public function __construct(array $data = [], array $class = [])
    {
        $this->data = $data;
        $this->class = $class;
    }

    /**
     * Return element value
     * @param string $index
     * @return string|null
     */

    private function getValue(string $index): string|null
    {
        return $this->data[$index] ?? null;
    }

    /**
     * Return element classes
     * @param array $class
     * @return string|null
     */

    private function getClass(array $class): ?string
    {
        foreach($class as $c => $k){
            $cl[] = $k;
        }
        // classes
        foreach ($this->class as $tc => $tk) {
            $cl[] = $tk;
        }
        if(isset($cl)) return join(' ', $cl);
        return null;
    }

    /**
     * Input element
     * @param string $name
     * @param string $type
     * @param array $class
     * @return Form
     */

    public function input(string $name, string $type = "text", array $class = []): self
    {
        echo '<input type="'. $type .'" name="'. $name .'" value="'. $this->getValue($name) .'" class="'. $this->getClass($class) . '">';
        return $this;
    }

    /**
     * Button element
     * @param string $name
     * @param string $type
     * @param array $class
     * @return Form
     */

    public function button(string $name, string $type = "button", array $class = []): self
    {
        echo '<button type="'. $type .'" name="'. $name .'" value="'. $this->getValue($name) .'" class="'. $this->getClass($class) .'"></button>';
        return $this;
    }

    /**
     * Select element
     * @param string $name
     * @param array $class
     * @param array $options
     * @param bool $multiple
     * @return $this
     */

    public function select(string $name, array $class = [], array $options = [], bool $multiple = false): self
    {

        echo '<select name="'. $name .'" class="'. $this->getClass($class) .'" >';

        return $this;
    }

    /**
     * @param string $text
     * @param string|int $value
     * @return $this
     */

    public function option(string $text, string|int $value): self
    {

        echo '<option value="'. $value .'">'. $text .'</option>';

        return $this;
    }

    /**
     * @return $this
     */

    public function endselect(): self
    {
        echo '</select>';
        return $this;
    }

    /**
     * Submit button
     * @param array $class
     * @return Form
     */

    public function submit(array $class = []): self
    {
        echo '<button type="submit" class="'. $this->getClass($class) . '">Send</button>';
        return $this;
    }

}