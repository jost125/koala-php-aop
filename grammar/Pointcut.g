grammar Pointcut;
options{
	language = Php;
	k = 1;
}

WS  :   ( ' '
        | '\t'
        | '\r'
        | '\n'
        )
    ;
    
pointcut_grammar
	:	POINTCUT_EXPRESSION;

POINTCUT_EXPRESSION
	:	POINTCUT (WS+ POINTCUT_OPERATOR WS+ POINTCUT_EXPRESSION)?
	|	'(' WS* POINTCUT_EXPRESSION WS* ')';

fragment
POINTCUT
	:	'execution' WS* '(' WS* MODIFIER WS+ CLASS_EXPRESSION '::' METHOD_EXPRESSION '(' WS* (ARGUMENTS_EXPRESSION WS*)? ')' WS* ')'
	|	'methodAnnotated' WS* '(' WS* CLASS_EXPRESSION WS* ')';

fragment
MODIFIER
	:	'public' | 'private' | 'protected' | '*';

fragment
POINTCUT_OPERATOR
	:	'and' | 'or';

fragment
CLASS_EXPRESSION
	:	(('*' | '\\') ID)+ | '*'; 

fragment
METHOD_EXPRESSION
	:	(ID | '*')+;

fragment
ID  :	('a'..'z'|'A'..'Z'|'_') ('a'..'z'|'A'..'Z'|'0'..'9'|'_')*
    ;

fragment
ARGUMENT
	:	('var' | (('\\') ID)+ ) WS+ '$' ID | '$' ID;

fragment
ARGUMENTS_EXPRESSION
	:	'..' | ARGUMENT (WS*','WS* ARGUMENT)*;
