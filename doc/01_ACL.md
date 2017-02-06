Access Control List
===================

Moduł list kontroli dostępu definiuje nam jaka Tożsamość powinna mieć dostęp do danego zasobu.
Zasoby mogą być filtrowane już na poziomie zapytania bądź poza bazą na samym zasobie.

Pojęcia:
--------

* Reguła (Rule) - Regułą nazywamy klasę, która przyznaje nam dostęp do Zasobu wg jej ustawień,
* Zasób (Resource) - obiekt do którego chcemy ograniczyć dostęp. Zasób definiuje nam Regułę, która ma być egzekwowana,
dodatkowe customowe uprawnienia, nazwę oraz poziom dostępności (requireRoot)
* Akcja (Action) - definiuje zachowanie przydzielania dostępu dla konkretnej akcji na zasobie. Może być rejestrowana przez różne moduły dla konkretnego Rula,
* Tożsamość (Identity) - jest identyfikowana przez unikalne id, definiuje czy jest Rootem czy anonimowym, czy posiada pełne uprawnienia. Jest także tworem kaskadowym więc może być czyimś 
rodzicem bądź może sam mieć rodzica, jednak odwołanie zawsze działa w stronę rodzica.
 
 

Zasady przyznawania dostępu poza Regułami:
------------------------------------------

* If Identity is Root `Allow All`
* If Identity is Anonymous `Deny All`
* If Resource require Root and Identity is Root `Allow All`
* If Resource require Root and Identity is no Root `Deny All`
* If Resource not Require Root and Identity has full permission `Allow All`
* If Resource not Require Root and Identity has no full permission execute Rule to `Allow` or `Deny`


Zależności:
-----------

* Doctrine w wersji v2.5.4 lub wyższej
* Acl musi mieć zdefiniowaną Tożsamość przed próbą dostępu do jakiegokolwiek zasobu.