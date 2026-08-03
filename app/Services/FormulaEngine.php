<?php

namespace App\Services;

class FormulaEngine
{
    protected array $variables = [];

    public function setVariables(array $vars): self
    {
        $this->variables = $vars;
        return $this;
    }

    public function evaluate(string $formula): mixed
    {
        $expr = $formula;

        foreach ($this->variables as $key => $value) {
            if (is_string($value)) {
                $value = '"' . addslashes($value) . '"';
            } elseif (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            } elseif (is_null($value)) {
                $value = '0';
            }
            $expr = str_replace($key, (string) $value, $expr);
        }

        // if(a, b, c) → (a ? b : c)
        $expr = preg_replace_callback(
            '/if\s*\(([^,]+),\s*([^,]+),\s*([^)]+)\)/',
            fn($m) => "({$m[1]} ? {$m[2]} : {$m[3]})",
            $expr
        );

        if (!preg_match('/^[\d\s+\-\/\*\.\(\),<>=!&|?:_a-zA-Z"\\\\]+$/', $expr)) {
            throw new \Exception("Caractères non autorisés");
        }

        $expr = '$__result = (' . $expr . ');';
        
        try {
            eval($expr);
            return $__result ?? 0;
        } catch (\Throwable $e) {
            throw new \Exception("Erreur: " . $e->getMessage());
        }
    }

    public function test(): bool
    {
        $this->setVariables(['a' => 10, 'b' => 5]);
        return $this->evaluate('min(a + b, 20)') === 15;
    }
}