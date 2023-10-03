import "./bootstrap";

// alert("websocket");

let publicChannel = Echo.channel("public.like.3");
publicChannel
    .subscribed(() => {
        console.log("publicChannel subscribed");
    })
    .listen(".server.message.like", (event) => {
        console.log(event);
    });

    let send = document.getElementById("send");

send.addEventListener("click", () => {
    axios.get("/user/like/list", {
        message: "hello",
    });
});

// import Alpine from "alpinejs";

// window.Alpine = Alpine;

// Alpine.start();
