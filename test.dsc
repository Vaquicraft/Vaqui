traitement_connexion:
  type: world
  debug: false
  events:
    on player clicks in inventory:
    - if <player.has_flag[login]>:
      - inventory open d:membre
    - else:
      - inventory open d:index
      - define pseudo <player.flag[input.pseudo]>
      - define password <player.flag[input.password]>
      - if !<server.flag[users].contains[<[pseudo]>]>:
        - narrate "Cet utilisateur n'existe pas."
        - inventory close
        - inventory open d:index
      - if !<[pseudo].exists>:
        - inventory close
        - inventory open d:index
      - if <server.flag[users.<[pseudo]>.password].equals[<[password]>]>:
        - narrate "Mauvais identifiants."
      - else:
        - flag player login
        - inventory close
        - inventory open d:index