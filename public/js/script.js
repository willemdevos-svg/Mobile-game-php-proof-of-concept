// Arteficial display of timeTokens counter.
console.log('js');
const counterElement = document.getElementById("counter");
let count = parseInt(counterElement.textContent, 10);
if (count > 20) {
    count = 20;
    counterElement.textContent = count;
} else {
    const interval = setInterval(() => {
        count++;
        counterElement.textContent = count;
        if (count >= 20) {
            clearInterval(interval);
        }
    }, 1000);
}