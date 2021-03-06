<?php

namespace grigor\generator\scanner\support;

class AnnotationFeatureParser
{
    public function parse(array $tokens): array
    {
        $result = [];

        $waitingClassName = false;
        $waitingNamespace = false;
        $waitingUse = false;
        $waitingUseSeparator = false;
        $waitingNamespaceSeparator = false;
        $namespace = [];
        $use = [];

        foreach ($tokens as $i => $iValue) {
            if (is_array($iValue)) {
                list($id, $value) = $iValue;
                switch ($id) {
                    case T_NAMESPACE:
                        $waitingNamespace = true;
                        $waitingNamespaceSeparator = false;
                        $namespace = [];
                        break;
                    case T_USE:
                        $waitingUse = true;
                        $waitingUseSeparator = false;
                        $use = [];
                        break;
                    case T_CLASS:
                    case T_INTERFACE:
                        $waitingClassName = true;
                        break;

                    case T_STRING:
                        if ($waitingNamespace) {
                            $namespace[] = $value;
                            $waitingNamespace = false;
                            $waitingNamespaceSeparator = true;
                        } elseif ($waitingClassName) {
                            if (!empty($namespace)) {
                                $value = sprintf('%s\\%s', implode('\\', $namespace), $value);
                            }
                            $result['class'] = $value;
                            return $result;
                        }
                        if ($waitingUse) {
                            $use [] = $value;
                            $waitingUse = false;
                            $waitingUseSeparator = true;
                        }
                        break;
                    case T_NS_SEPARATOR:
                        if ($waitingNamespaceSeparator && !$waitingNamespace && !empty($namespace)) {
                            $waitingNamespace = true;
                            $waitingNamespaceSeparator = false;
                        }
                        if ($waitingUseSeparator && !$waitingUse && !empty($use)) {
                            $waitingUse = true;
                            $waitingUseSeparator = false;
                        }
                        break;
                }
            } else {
                if (($waitingNamespace || $waitingNamespaceSeparator) && ($iValue == '{' || $iValue == ';')) {
                    $waitingNamespace = false;
                    $waitingNamespaceSeparator = false;
                }
                if (($waitingUse || $waitingUseSeparator) && ($iValue == '{' || $iValue == ';')) {
                    $waitingUse = false;
                    $waitingUseSeparator = false;
                    $result['use'][] = implode('\\', $use);
                }
            }
        }
        return $result;
    }
}