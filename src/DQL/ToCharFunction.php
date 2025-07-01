<?php

namespace App\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

/**
 * ToCharFunction ::= "TO_CHAR" "(" ArithmeticPrimary "," StringPrimary ")"
 */
class ToCharFunction extends FunctionNode
{
    public $firstExpression;
    public $secondExpression;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $this->firstExpression = $parser->ArithmeticPrimary();

        $parser->match(TokenType::T_COMMA);

        $this->secondExpression = $parser->StringPrimary();

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return 'TO_CHAR(' .
            $sqlWalker->walkArithmeticPrimary($this->firstExpression) .
            ',' .
            $sqlWalker->walkStringPrimary($this->secondExpression) .
            ')';
    }
}
