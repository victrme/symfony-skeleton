import { Message } from "../entities/Message"
import { User } from "../entities/User"

globalThis.window.addEventListener('DOMContentLoaded', () => {
    User.parse({
        id: 1,
        email: "admin@example.com",
        roles: "[]"
    })

    Message.parse({})
})
