Zasób (Resource):
=================

Chcąc ograniczyć dostęp do danego obiektu musimy z niego utworzyć Zasób implementując interfejs `BCode\Acl\Core\Entity\Interfaces\ResourceInterface`
Zasób wymaga zdefiniowania symbolu reguły egzekwującej uprawnienia, nazwy zasobu oraz czy dany zasób wymaga Tożsmości Roota. Dodatkowo możemy zdefiniować 
dodatkowe customowe upoważnienia dostępne tylko w danym Zasobie.