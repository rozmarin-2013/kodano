### Projekt został stworzony przy użyciu Symfony 7.2 oraz API Platform 4.1.

### Instalacja i uruchomienie projektu

## Wymagania:

- Docker
- Docker Compose

## Kroki:

1. Jeśli używasz systemu Linux, wystarczy uruchomić polecenie, inne polecenia znajdziesz w Makefile:
    ```bash
    make init
    ```

2. Dla innych systemów operacyjnych wykonaj polecenia ręcznie:

   - Uruchom Docker Compose, aby zbudować i uruchomić kontenery:
     ```bash
     docker-compose up -d --build
     ```
   - Zainstaluj zależności:
     ```bash
       docker exec kodano_php composer install 
     ```  
   - Po pomyślnym uruchomieniu kontenerów wykonaj migracje bazy danych:
     ```bash
      docker exec kodano_php php bin/console doctrine:migrations:migrate
     ``` 
   - Uruchom fixture:
      ```bash
        docker exec kodano_php php bin/console doctrine:fixtures:load --no-interaction
     ```
   - Uruchom testy (jeśli wymagane):
     ```bash
       docker-compose exec php php bin/phpunit
     ```

## Projekt jest dostępny pod adresem

- [http://localhost:8080/api](http://localhost:8080/api)

## Dokumentacja API

- [http://localhost:8080/api/docs](http://localhost:8080/api/docs)

### Powiadomienia

W projekcie została zaimplementowana możliwość powiadomień za pomocą e-maila.  
Jeśli zajdzie potrzeba zmiany powiadamiania, wystarczy stworzyć klasę powiadamiania i zaimplementować ją z  
`App\Notification\NotificationInterface`, a następnie zamienić nowy powiadamiacz w pliku `conf/services.yaml`:
   - App\Notification\NotificationInterface: '@App\Notification\NotificationEmail'


### Inne polecenia
- Zatrzymanie kontenerów:
    ```bash
    docker-compose down
    ```




  


