Akcja (Action):
===============

Jako że resource może mieć tylko jedną regułę, ale wiele różnych wątków zwanych akcjami niezbędne było utworzenie rejestrowalnych akcji i ich zachowań.
Aby utworzyć akcję należy utworzyć klasę rozszerzającą `BCode\Acl\Core\AbstractAction` oraz w metodzie `run` utworzyć logikę przyznawania dostępu dla Tożsamości w danym Zasobie.
Dodatkowo niezbędne jest "podlinkowanie" akcji pod regułę poprzez abstrakcyjną metodę `getFor`.

Akcja musi posiadać Tożsamość, Zasób oraz opcjonalnie Uprawnienia (Permission), jeśli istnieją zdefiniowane dla danej akcji.

Uwaga:

* Akcja rejestrje się zawsze do jednej reguły.
* Uprawnienia (permission) akcji są już połączone z uprawnieniami rodzica.