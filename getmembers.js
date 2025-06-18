const fs = require("fs");

let title = document.createElement("div")
title.id = "title"
title.textContent = "Liste des utilisateurs : "
document.body.appendChild(title)

let data = fs.readFileSync("/bdd/users.json", 'utf8')
let users = JSON.parse(data)
res.writeHead(200, { "Content-Type": "text/plain; charset=utf-8" });
console.log(users)

// if (users.length <= 1) {
//     console.log("Erreur : aucun utilisateur n'a été trouvé dans la base de données.")
// } else {
//     users.forEach(user => {
//         console.log(user.id, user.name)
//     })
// }
