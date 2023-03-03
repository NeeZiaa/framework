
# FiveM MDT API DOC
___
+ ## Admin :
___
+ ### Settings :

    + GET /admin/settings : renvoies les paramètres actuels (json)
    + POST /admin/settings/update : url de modification des paramètres.
      Paramètres :
        + User token (string)
        + Settings (json)
          + Name (string)
          + Logo (form-data)
          + Base de données FiveM
            + Host
            + User
            + Password
            + Port
+ ### Jobs :

    + __GET /admin/jobs__ : retourne les jobs (json)
    + __GET /admin/jobs/search/:id__ : retourne la recherche d'un utilisateur par id/nom
      Paramètres :
        + User token (string)
        + Id/Name/FirstName/Username (string|int)
    + __GET /admin/jobs/:id__ : retourne les informations d’un job (json)

      Paramètres :
        + User token (string)
        + Id (int) Id du job
    + __POST /admin/jobs/create__ : création d’un job

      Paramètres :
        + User token (string)
        + Name (string) nom de repère
        + Label (string) nom affiché
    + __POST /admin/jobs/update/:id__ : modification d’un job

      Paramètres :
        + User token (string
        + Id (int) id du job
        + Name (string) nom de repère
        + Label (string) nom affiché
    + __POST /admin/jobs/delete/:id__ : suppression d’un job

      Paramètres :
        + User token (string)
        + Id (int) Id du job

    + ### Ranks
        + __GET /admin/jobs/:id/ranks__ : retourne les ranks d'un job (json)

          Paramètres :
            + User token (string)
            + Id (int) Id du job
        + __GET /admin/jobs/ranks/:id__ : retourne les informations d'un rank (json)

          Paramètres :
            + User token (string)
            + Id (int) Id du rank
        + __POST /admin/jobs/ranks/create__ : création d'un job

          Paramètres :
            + User token (string)
            + Name (string) nom du job
            + Label (string) nom affiché
            + Weight (int) poids du job
            + Permissions (json) permissions du rank sous forme de json
            + Default interface (json) optionnel
        + __POST /admin/jobs/ranks/update/:id__ : création d'un job

          Paramètres :
            + User token (string
            + Rank ID (int)
            + Name (string) nom du job
            + Label (string) nom affiché
            + Weight (int) poids du job
            + Permissions (json) permissions du rank sous forme de json
            + Default interface (json) optionnel
        + __POST /admin/jobs/ranks/delete/:id__ : création d'un job

          Paramètres :
            + User token (string)
            + Rank ID (int)
+ ### Users
    + __GET /admin/users__ : retourne les utilisateurs (json)

      Paramètres :
        + User token (string)

    + __GET /admin/users/search/:id__ : recherche d'un utilisateur (json)

      Paramètres :
        + User token (string)
        + Id/Username/Firstname/Name (string|int)

    + __POST /admin/users/create__ : création d'un utilisateur

      Paramètres :
        + User token (string)
        + Username (string)
        + Firstname (string)
        + Name (string)
        + Job (int)

    + __POST /admin/users/update__ : modification d'un utilisateur

      Paramètres :
        + User token (string)
        + Username (string)
        + Firstname (string)
        + Name (string)
        + Job (int)

    + __POST /admin/users/delete__ : suppression d'un utilisateur

      Paramètres :
        + User token (string)

+ ## Public
    + __POST /login__ : connexion
  
      Paramètres :
        + Username (string)
        + Password (string)
    
        Return :
        + Token (string) connexion ok
        + False (bool) echec
    
+ ### Boss actions
    + __GET /company/employees__ : Liste des employés d'une entreprise (json)

      Paramètres :
        + User token (string)
      
    + __POST /company/employees/add__ : Ajouter un employé 

      Paramètres :
        + User token (string)
        + Username (string)
        + Firstname (string)
        + Name (string)
        + Job (int)
        + Age (int)

    + __POST /company/employees/update__ : Modifier d'un employé

      Paramètres :
        + User token (string)
        + Employee ID (int)
        + Username (string)
        + Firstname (string)
        + Name (string)
        + Job (int)
        + Age (int)

    + __POST /company/employees/delete__ : Supprimer un employé

      Paramètres :
        + User token (string)
        + Employee ID (int)

    + __GET /company/money__ : Argent (string)
      Paramètres :
        + User token (string)
    
    + __GET /company/sales__ : Liste des ventes (json)
      Paramètres :
        + User token
  
    + __GET /company/infos