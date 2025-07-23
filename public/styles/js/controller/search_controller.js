import { Controller } from "../hotwired/stimulus.js"

export default class extends Controller {
  static targets = ["form", "input", "container", "results"]

  connect() {
    this.delay = null
    // Pour détecter les clics en-dehors
    this.onClickOutside = this.onClickOutside.bind(this)
      document.addEventListener("click", this.onClickOutside)
  }

  disconnect() {
    document.removeEventListener("click", this.onClickOutside)
  }

  prevent(event) {
    event.preventDefault()
  }

  stopPropagation(event) {
    // pour que le listener global ne cache pas immédiatement
    event.stopPropagation()
  }

  onInput() {
    clearTimeout(this.delay)
    const query = this.inputTarget.value.trim()
    this.delay = setTimeout(() => this.fetchResults(query), 300)
  }

  async fetchResults(q) {
    if (!q) return this.hideResults();
  
    const url = new URL("/webtoon/search", window.location.origin);
    url.searchParams.set("q", q);
  
    const resp = await fetch(url, {
      headers: {
        "Accept": "text/html",
        "X-Requested-With": "XMLHttpRequest"
      }
    });
    if (!resp.ok) throw new Error("Erreur réseau");
    const html = await resp.text();
  
    this.resultsTarget.innerHTML = html;
    if (html.trim()) this.showResults();
    else this.hideResults();
  }

  showResults() {
    this.containerTarget.hidden = false
  }

  hideResults() {
    this.containerTarget.hidden = true
    this.resultsTarget.innerHTML = ""
  }

  onClickOutside(event) {
    // si click hors de la div controller, on cache
    if (!this.element.contains(event.target)) {
      this.hideResults()
    }
  }
}