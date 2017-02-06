Filtrowanie Query (Query Filter):
=================================

Kolejnym sposobem definiowania poziomu dostępności do zasobu jest Filtr Query. Każdy filtr może wstrzykiwać jeden warunek where dla jednego obiektu DAO.
Jednak może nim być także warunek Where and gdzie będzie kilka warunków na raz.

Filtry budujemy rozszerzając klasę `BCode\Acl\Core\Query\AbstractFilter` uzupełniając metodę `doGetConditions`, która zwraca DAO_Condition. Dodatkowo filtr musi odnosić się do jakiegoś zasobu w wypadku filtrów zawsze będzie to jakaś encja
Opcjonalnie możemy w filtrze zaimplementować metodę `beforeFilter()`.


HINT:
-----

Filtry mogą korzystać także z uprawnień Zasobów. Chcąc użyć uprawnień Zasobu w Filtrze należy jako nazwę Zasobu podać klasę obiektu DAO (`\Product::class`),
a następnie w Filter powiązać z Zasobem poprzez nadanie mu tego Zasobu w `getResource`. 
W ten sposób jeśli istnieje dla danego Zasobu i Tożsamości (oraz jego rodzica) uprawnienie zostanie przekazane do Filtra.