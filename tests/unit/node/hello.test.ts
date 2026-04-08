import { beforeEach, describe, expect, it } from 'vitest'
import { buttonCounter } from '../../../assets/scripts/hello.ts'

describe('buttonCounter', () => {
    beforeEach(() => {
        document.body.innerHTML = '<button data-hello-btn>Click me</button>'
    })

    it('updates button text on click', () => {
        buttonCounter()
        const btn = document.querySelector<HTMLElement>('[data-hello-btn]')

        btn?.click()
        expect(btn?.textContent).toBe('Clicked 1 times')
        btn?.click()
        expect(btn?.textContent).toBe('Clicked 2 times')
    })
})
