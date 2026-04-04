globalThis.window.addEventListener('DOMContentLoaded', () => {
    buttonCounter()
    toggleCheckboxes()
    updateVerify()
})

export function buttonCounter() {
    const btn = document.querySelector<HTMLButtonElement>('[data-hello-btn]')
    let counter = 1

    btn?.addEventListener('click', () => {
        btn.textContent = `Clicked ${counter++} times`
    })
}

export function toggleCheckboxes() {
    for (const checkbox of getCheckboxes()) {
        const name = checkbox.dataset.helloCheck
        const key = `hello-check:${name}`

        checkbox.checked = localStorage.getItem(key) === '1'

        checkbox.addEventListener('change', () => {
            const value = checkbox.checked ? '1' : '0'
            localStorage.setItem(key, value)
            updateVerify()
        })
    }
}

export function updateVerify() {
    const counterName = '[data-hello-verify-count]'
    const verifyName = '[data-hello-verify-done]'
    const counter = document.querySelector<HTMLElement>(counterName)
    const verify = document.querySelectorAll<HTMLElement>(verifyName)

    const checkboxes = getCheckboxes()
    const remaining = checkboxes.filter((c) => !c.checked).length
    const allDone = remaining === 0

    verify.forEach((paragraph) => {
        const isVerifyDone = paragraph.dataset.helloVerifyDone === 'true'
        paragraph.hidden = isVerifyDone !== allDone
    })

    if (counter) {
        counter.textContent = String(remaining)
    }
}

export function getCheckboxes(): HTMLInputElement[] {
    const selector = '[data-hello-check]'
    const checkboxes = document.querySelectorAll<HTMLInputElement>(selector)

    return Object.values(checkboxes)
}
