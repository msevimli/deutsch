<?php
require __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$rows = db()->query('SELECT id, kategorie, deutsch, tuerkisch, satz_deutsch, satz_tuerkisch FROM words ORDER BY id')->fetchAll();

$words = [];
$categories = [];
foreach ($rows as $row) {
    $cat = $row['kategorie'];
    $categories[$cat] = $cat;
    $words[] = [
        'de' => $row['deutsch'],
        'tr' => $row['tuerkisch'],
        'cat' => $cat,
        'sd' => $row['satz_deutsch'],
        'st' => $row['satz_tuerkisch'],
    ];
}

shuffle($words);

$jsonFlags = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
$username  = htmlspecialchars($_SESSION['username']);
$pageTitle = 'Word Trainer';
require __DIR__ . '/header.php'; ?>

<main class="game">
  <div class="game-head">
    <h1>Word Trainer</h1>
    <p class="sub">Click the card to reveal the Turkish translation.</p>
  </div>

  <div class="game-bar">
    <label for="category">Category</label>
    <select id="category">
      <option value="">All categories</option>
      <?php foreach ($categories as $key => $label): ?>
        <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="button" id="shuffle" class="btn ghost">Sh</button>
    
  </div>

  <div class="counter"><span id="pos">0</span> / <span id="total">0</span></div>

  <div class="flip-card" id="card" role="button" tabindex="0" aria-label="Flashcard, click to flip">
    <div class="flip-inner">
      <div class="face front">
        <span class="badge" id="frontCat"></span>
        <p class="word" id="frontWord"></p>
        <p class="sentence" id="frontSatz"></p>
        <span class="tap-hint">click to reveal</span>
      </div>
      <div class="face back">
        <span class="badge" id="backCat"></span>
        <p class="word tr" id="backWord"></p>
        <p class="sentence" id="backSatz"></p>
        <span class="tap-hint">click to flip back</span>
      </div>
    </div>
  </div>

  <div class="nav-buttons">
    <button type="button" id="prev" class="btn ghost">&larr; Previous</button>
    <button type="button" id="toggleSatz" class="btn ghost" aria-pressed="false">S</button>
    <button type="button" id="next" class="btn">Next &rarr;</button>
  </div>
</main>

<script>
const WORDS = <?= json_encode($words, $jsonFlags) ?>;
const CAT_LABELS = <?= json_encode($categories, $jsonFlags) ?>;

let deck = WORDS.slice();
let idx = 0;

const card = document.getElementById('card');
const posEl = document.getElementById('pos');
const totalEl = document.getElementById('total');
const frontWord = document.getElementById('frontWord');
const frontCat = document.getElementById('frontCat');
const backWord = document.getElementById('backWord');
const backCat = document.getElementById('backCat');
const frontSatz = document.getElementById('frontSatz');
const backSatz = document.getElementById('backSatz');

let sentencesOn = localStorage.getItem('satz') !== 'off';
const satzBtn = document.getElementById('toggleSatz');

function applySatz(w) {
    const show = sentencesOn && w && w.sd;
    frontSatz.textContent = show ? w.sd : '';
    backSatz.textContent = show ? w.st : '';
    frontSatz.classList.toggle('hidden', !show);
    backSatz.classList.toggle('hidden', !show);
}

satzBtn.classList.toggle('active', sentencesOn);
satzBtn.setAttribute('aria-pressed', String(sentencesOn));

function render() {
    if (deck.length === 0) {
        frontWord.textContent = 'No words';
        backWord.textContent = '-';
        frontCat.textContent = '';
        backCat.textContent = '';
        posEl.textContent = '0';
        totalEl.textContent = '0';
        applySatz(null);
        return;
    }
    const w = deck[idx];
    frontWord.textContent = w.de;
    frontCat.textContent = w.cat;
    backWord.textContent = w.tr;
    backCat.textContent = w.cat;
    posEl.textContent = idx + 1;
    totalEl.textContent = deck.length;
    applySatz(w);
    card.classList.remove('flipped');
}

function go(step) {
    if (deck.length === 0) return;
    idx = (idx + step + deck.length) % deck.length;
    render();
}

function shuffleDeck(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
}

card.addEventListener('click', () => {
    if (deck.length > 0) card.classList.toggle('flipped');
});
card.addEventListener('keydown', e => {
    if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        if (deck.length > 0) card.classList.toggle('flipped');
    }
});

document.getElementById('prev').addEventListener('click', () => go(-1));
document.getElementById('next').addEventListener('click', () => go(1));

document.getElementById('shuffle').addEventListener('click', () => {
    shuffleDeck(deck);
    idx = 0;
    render();
});

satzBtn.addEventListener('click', () => {
    sentencesOn = !sentencesOn;
    localStorage.setItem('satz', sentencesOn ? 'on' : 'off');
    satzBtn.classList.toggle('active', sentencesOn);
    satzBtn.setAttribute('aria-pressed', String(sentencesOn));
    if (deck.length > 0) applySatz(deck[idx]);
});

document.getElementById('category').addEventListener('change', e => {
    const key = e.target.value;
    deck = key === '' ? WORDS.slice() : WORDS.filter(w => w.cat === CAT_LABELS[key]);
    idx = 0;
    render();
});

document.addEventListener('keydown', e => {
    if (e.key === 'ArrowLeft') go(-1);
    if (e.key === 'ArrowRight') go(1);
});

render();
</script>
</body>
</html>
