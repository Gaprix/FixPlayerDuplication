# FixPlayerDuplication
LiteCore 1.0.9 plugin to fix a vulnerability with the possibility of logging in by two players with the same nicknames
Плагин для LiteCore 1.0.9, который исправляет уязвимость, позволяющую двум игрокам зайти под одинаковым ником, заменив строчную букву ника на прописную

# Events
В плагине содержится ивент, который вызывается при заходе двух игроков под одинаковыми никами. Вы можете отменить его по желанию
```
public function onPlayerDuplication(\Gaprix\FixPlayerDuplication\PlayerDuplicateLoginEvent $event)
{

}
```
