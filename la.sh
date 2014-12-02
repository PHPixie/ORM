# User@Host: dracony[dracony] @ localhost []
# Query_time: 0.000452  Lock_time: 0.000109  Rows_sent: 1  Rows_examined: 1
SELECT id FROM users WHERE name='Pixie';
# User@Host: dracony[dracony] @ localhost []
# Query_time: 0.001943  Lock_time: 0.000145  Rows_sent: 0  Rows_examined: 0
INSERT INTO posts (title, text)VALUES('Hello', 'World');