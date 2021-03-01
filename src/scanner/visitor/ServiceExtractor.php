<?php

namespace grigor\generator\scanner\visitor;

interface ServiceExtractor
{
    public function extract(): array;
}