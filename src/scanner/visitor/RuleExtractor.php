<?php


namespace grigor\generator\scanner\visitor;


interface RuleExtractor
{
    public function extract(): array;
}