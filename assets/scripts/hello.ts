globalThis.window.addEventListener('DOMContentLoaded', () => {
    const btn = document.querySelector<HTMLButtonElement>('[data-hello-btn]')
    let counter = 1

    btn?.addEventListener('click', function() {
        btn.textContent = `Clicked ${counter++} times`
    })
})
