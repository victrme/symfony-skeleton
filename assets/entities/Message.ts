import { z } from "../libs/zod"

export const Message = z.object({
    id: z.uuid(),
    subject: z.string(),
    body: z.string(),
    sender: z.uuid(),
    recipient: z.uuid(),
    createdAt: z.iso.datetime(),
    readAt: z.iso.datetime()
});