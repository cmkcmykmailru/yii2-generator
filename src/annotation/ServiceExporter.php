<?php

namespace grigor\generator\annotation;

interface ServiceExporter
{
    public function export(): array;
}