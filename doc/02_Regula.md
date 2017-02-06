Reguła (Rule):
==============


Poza standardową zależnością między Zasobem i Tożsamością sterowanie przydzielaniem dostępu umożliwia nam Reguła.
Aby utworzyć regułę należy utworzyć klasę rozszerzającą `BCode\Acl\Core\AbstractRule` oraz w metodzie `doExecute` utworzyć logikę przyznawania dostępu dla Tożsamości.
Reguła powinna definiować logikę wspólną dla Zasobu np. Czy przekazany został odpowiedni zasób lub identity spełnia zależność, zaś reszta logiki umieszczamy w poszczególnych akcjach.

Reguła ma wstrzyknięte ustawienia uprawnień Permission jeśli je posiada, jednak może działać także bez niej, w zależności od autora.

Uwaga:

* Zasób może odnosić się tylko do jednej reguły!
* Uprawnienia (permission) w regule, bądz akcji są już połączone z uprawnieniami rodzica