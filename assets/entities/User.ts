import { z } from "../libs/zod"

export const User = z.object({
    id: z.uuid(),
    email: z.string(),
    roles: z.array(z.string())
});