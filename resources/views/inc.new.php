Административная ссылка:
<input size="70" onClick="this.select();" value="http://<?=filter_input(INPUT_SERVER, 'SERVER_NAME');
?>/<?=$url['admin']?>" />
<br>
Ссылка для просмотра:
<input size="40" onClick="this.select();" value="http://<?=filter_input(INPUT_SERVER, 'SERVER_NAME');
?>/<?=$url['user']?>" />