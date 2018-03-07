# Дополнения к Eloquent

Эти дополнения изначально были реализованы на Yii2 (наследованием от ActiveRecord и расширением ActiveQuery для этого ActiveRecord)

У Eloquent другой подход - Scope

## Статус записи

Подробнее в docs/attributes/e_onoff.md

В Eloquent есть штатный механизм мягкого удаления (пометки записи как удаленной)

Расширяет и заменяет этот механизм trait OnOffDeletes.  
Можно использовать и без поля deleted_at. Для этого в модели нужно добавить:  
protected $deleted_at_column = false;

**НЕ реализовано**  
При работе без поля deleted_at будет выброшена ошибка, потому что в 
HasManyThrough
есть
$query->whereNull($this->throughParent->getQualifiedDeletedAtColumn());

а getQualifiedDeletedAtColumn() вернет null
