grammar pointcut;

ID  :	('a'..'z'|'A'..'Z'|'_') ('a'..'z'|'A'..'Z'|'0'..'9'|'_')*
    ;

WS  :   ( ' '
        | '\t'
        | '\r'
        | '\n'
        ) {$channel=HIDDEN;}
    ;
    
    
POINTCUT_EXPRESSION
	:	POINTCUT (WS* POINTCUT_OPERATOR WS* POINTCUT_EXPRESSION)*
	|	'(' WS* POINTCUT_EXPRESSION WS* ')';

POINTCUT_OPERATOR
	:	'&&' | '||';

fragment
POINTCUT
	:		'execution' WS* '(' WS* MODIFIER WS+ CLASS_EXPRESSION '::' METHOD_EXPRESSION '(' WS* ARGUMENTS_EXPRESSION WS* ')' WS* ')';
	
MODIFIER
	:	'public' | 'private' | 'protected' | '*';

CLASS_EXPRESSION
	:	(('*' | '\\') ID)+ | '*'; 
	
METHOD_EXPRESSION
	:	(ID | '*')+;
	
ARGUMENTS_EXPRESSION
	:	'..' | ARGUMENT (WS*','WS* ARGUMENT)* | ;
	
ARGUMENT
	:	('var' | ID WS+ '$' ID | '$' ID);
	