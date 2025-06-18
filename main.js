const http = require("http")

const server = http.createServer((req, res) => {

    if (req.url === "/user") {
        console.log("Bonne page !")
    }
    res.end()


})

server.listen(3000, () => {
    console.log("Serveur en ligne sur http://localhost:3000")

})