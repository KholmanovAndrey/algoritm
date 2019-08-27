<?php

class Node {
    public $value;
    public $const;
    public $var;
    public $left;
    public $right;


    public function __construct($value)
    {
        $this->value = $value;
        $this->right = null;
        $this->left = null;
    }
}

class Tree {
    protected $root;
    protected $vars;

    public function __construct($str, $vars)
    {
        $arLec = $this->parse($str);
        $this->root = $this->insert($arLec);
        $this->vars = $vars;
    }

    protected function parse($str)
    {
        $str = mb_strtolower($str, 'UTF-8');
        $str = str_replace(" ", "", $str);
        $length = mb_strlen($str, 'UTF-8');
        $arStr = [];
        for ($i = 0; $i < $length; $i++) {
            $arStr[] = $str[$i];
        }

        $arLec = [];
        for ($i = 0; $i < $length; $i++) {
            if(preg_match("/\d/", $arStr[$i])){
                for ($j = $i; $j < $length; $j++) {
                    if(preg_match("/\d/", $arStr[$j])){
                        $arLec[$i] .= $arStr[$j];
                        $arStr[$j] = null;
                    } else {
                        break;
                    }
                }
            } else {
                $arLec[] .= $arStr[$i];
            }
        }
        $arLecNull = [];
        for ($i = 0; $i < count($arLec); $i++) {
            if ($arLec[$i] != null) {
                $arLecNull[] = $arLec[$i];
            }
        }
        $arLec = $arLecNull;
        return $arLec;
    }

    /**
     * определение точки перегиба выражения
     * @param $lec
     * @return int|string
     */
    protected function inflPoint($lec){
        $infl=0;
        $max=0;
        static $br = 0;
        static $arPrioritet = Array(
            "+" => 3,
            "-" => 3,
            "*" => 2,
            "/" => 2,
            "^" => 1);

        foreach ($lec as $key=>$value){
            if(preg_match("/^[\d.]/", $value)){
                continue;
            }
            if($value=="("){
                ++$br;
                continue;
            }
            if($value==")"){
                --$br;
                continue;
            }
            if($arPrioritet[$value]-3*$br >= $max){
                $max=$arPrioritet[$value]-3*$br;
                $infl=$key;
            }
        }
        return $infl;
    }

    protected function insert($arLec)
    {
        $index = $this->inflPoint($arLec);

        $root = $arLec[$index];

        $leftLec = array_slice($arLec, 0, $index);
        if ($leftLec[0]=="(" && $leftLec[count($leftLec)-1]==")") {
            array_shift($leftLec);
            array_pop($leftLec);
        }

        $rightLec = array_slice($arLec, $index+1);
        if ($rightLec[0]=="(" && $rightLec[count($rightLec)-1]==")") {
            array_shift($rightLec);
            array_pop($rightLec);
        }

        $leftN = $this->inflPoint($leftLec);
        $leftP = $leftLec[$leftN];
        $rightN = $this->inflPoint($rightLec);
        $rightP = $rightLec[$rightN];

        $node = new Node($root);
        if ($leftP === '+' ||
            $leftP === '-' ||
            $leftP === '*' ||
            $leftP === '/' ||
            $leftP === '^') {
            $node->left = $this->insert($leftLec);
        } else {
            $lNode = new Node(null);
            if(preg_match("/\d/", $leftP)){
                $lNode->const = $leftP;
            } else {
                $lNode->var = $leftP;
            }
            $node->left = $lNode;
        }
        if ($rightP === '+' ||
            $rightP === '-' ||
            $rightP === '*' ||
            $rightP === '/' ||
            $rightP === '^') {
            $node->right = $this->insert($rightLec);
        } else {
            $rNode = new Node(null);
            if(preg_match("/\d/", $rightP)){
                $rNode->const = $rightP;
            } else {
                $rNode->var = $rightP;
            }
            $node->right = $rNode;
        }

        return $node;
    }

    public function calc(&$node = null)
    {
        if (is_null($node)) {
            $node = &$this->root;
        }
        if (!is_null($this->root->const)) {
            return $this->root->const;
        }
        if (!is_null($node->left) && !is_null($node->left->left)) {
            if (!is_null($node->right->left)) {
                return $this->calc($node->right);
            } else {
                return $this->calc($node->left);
            }
        } else {
            $sum = 0;
            foreach ($this->vars as $key=>$value) {
                if ($node->left->var === $key) {
                    $node->left->const = $value;
                }
                if ($node->right->var === $key) {
                    $node->right->const = $value;
                }
            }
            if ($node->value === '+') {
                $sum = $node->left->const + $node->right->const;
            }
            if ($node->value === '-') {
                $sum = $node->left->const - $node->right->const;
            }
            if ($node->value === '*') {
                $sum = $node->left->const * $node->right->const;
            }
            if ($node->value === '^') {
                $sum = pow($node->left->const, $node->right->const);
            }
            if ($node->value === '/') {
                $sum = $node->left->const / $node->right->const;
            }
            $node->const = $sum;
            $node->left = null;
            $node->right = null;
            return $this->calc($this->root);
        }
    }
}

$vars['x'] = 2;
$vars['y'] = 3;
$vars['z'] = 4;

$tree = new Tree("(x+2)^2+7*y-z", $vars);
echo 'Результат = ' . $tree->calc();

