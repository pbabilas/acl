Tożsamość (Identity):
=====================

Tożsamość tworzymy implementując obiekt o interfejs `BCode\Acl\Core\Entity\Interfaces\Identity`. Jednoznacznie definiujemy unikalny id 
(musi być to unikalny ciąg dla całej kolekcji tożsamości - nie mylić z id danego obiektu bo to nie jest unikalne w tym kontekście)
Tożsamość może być Rootem, czyli posiadać dostęp do absolutnie wszystkich Zasobów, ale taże może być Anonimowy nie posiadający żadnego dostępu.
Dodatkowo tożsamość może mieć dostęp do wszystkich Zasobów prócz tych wymagających Roota.

Tożsamości definiowane są kaskadowo jednak tylko o jeden poziom co oznacza, że każda Tożsamość może mieć rodzica, jednak sprawdzanie uprawnień
ogranicza się do aktualnej tożsamości oraz jego rodzica (rodzica, rodzica już nie).