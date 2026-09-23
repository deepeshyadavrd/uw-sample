/**
 * room-preview-widget.js
 * -----------------------------------------------------------------
 * Drop-in widget for an OpenCart product page. Renders a button that
 * opens a modal; the customer uploads only their room photo. Product
 * name/image are read from data attributes you set on the trigger
 * button (values you already have in the OpenCart template).
 *
 * USAGE — add this to your product.twig (OpenCart 3 uses Twig, not raw
 * PHP, in its templates — drop this right after the add-to-cart button):
 *
 *   <button
 *     id="room-preview-trigger"
 *     data-product-name="{{ heading_title }}"
 *     data-product-image="{{ thumb }}"
 *     data-product-category=""
 *   >See it in your room</button>
 *
 *   <script src="/room-preview/room-preview-widget.js" data-endpoint="/room-preview/generate.php"></script>
 *
 * Notes:
 *   - `thumb` is the medium product image already loaded on the page.
 *     If you want the larger popup image instead, check what your
 *     theme's product.twig calls it (often `popup_image` inside the
 *     images loop) and swap it in.
 *   - `product_category` isn't a variable OpenCart's product page has
 *     by default — leave it blank (as above) unless you've added it
 *     yourself; the model will still infer placement from the name/image.
 *   - The data-endpoint attribute on the <script> tag points at generate.php.
 * -----------------------------------------------------------------
 */
(function () {
  const scriptTag = document.currentScript;
  const ENDPOINT = scriptTag.getAttribute('data-endpoint') || '/room-preview/generate.php';

  const trigger = document.getElementById('room-preview-trigger');
  if (!trigger) return; // nothing to attach to on this page

  const productName = trigger.getAttribute('data-product-name') || '';
  const productImage = trigger.getAttribute('data-product-image') || '';
  const productCategory = trigger.getAttribute('data-product-category') || '';

  // ---- Build modal markup once ----------------------------------
  const style = document.createElement('style');
  style.textContent = `
    .rpw-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.55);
      display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 16px; }
    .rpw-modal { background: #fff; border-radius: 10px; max-width: 480px; width: 100%;
      padding: 24px; box-sizing: border-box; position: relative; font-family: inherit; }
    .rpw-close { position: absolute; top: 12px; right: 16px; background: none; border: none;
      font-size: 22px; line-height: 1; cursor: pointer; color: #666; }
    .rpw-title { margin: 0 0 4px; font-size: 1.15rem; }
    .rpw-subtitle { margin: 0 0 16px; color: #666; font-size: 0.9rem; }
    .rpw-drop { border: 2px dashed #ccc; border-radius: 8px; padding: 24px; text-align: center;
      cursor: pointer; color: #666; }
    .rpw-drop.has-image { padding: 0; border-style: solid; }
    .rpw-drop img { max-width: 100%; max-height: 220px; display: block; margin: 0 auto; border-radius: 6px; }
    .rpw-file-input { display: none; }
    .rpw-actions { margin-top: 16px; display: flex; justify-content: flex-end; gap: 8px; }
    .rpw-btn { padding: 10px 18px; border-radius: 6px; border: none; cursor: pointer; font-size: 0.95rem; }
    .rpw-btn-primary { background: #222; color: #fff; }
    .rpw-btn-primary:disabled { background: #999; cursor: not-allowed; }
    .rpw-status { margin-top: 12px; font-size: 0.9rem; color: #666; min-height: 1.2em; }
    .rpw-status.error { color: #b00020; }
    .rpw-result { margin-top: 16px; text-align: center; }
    .rpw-result img { max-width: 100%; border-radius: 8px; border: 1px solid #eee; }
    .rpw-spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid #ccc;
      border-top-color: #222; border-radius: 50%; animation: rpw-spin 0.8s linear infinite;
      vertical-align: middle; margin-right: 8px; }
    @keyframes rpw-spin { to { transform: rotate(360deg); } }
  `;
  document.head.appendChild(style);

  const overlay = document.createElement('div');
  overlay.className = 'rpw-overlay';
  overlay.style.display = 'none';
  overlay.innerHTML = `
    <div class="rpw-modal">
      <button class="rpw-close" type="button" aria-label="Close">&times;</button>
      <h3 class="rpw-title">See it in your room</h3>
      <p class="rpw-subtitle">Upload a photo of your room — we'll place ${escapeHtml(productName)} in it for you.</p>

      <label class="rpw-drop" id="rpwDrop">
        <span id="rpwDropText">Click to upload a room photo</span>
        <input class="rpw-file-input" id="rpwFileInput" type="file" accept="image/*">
      </label>

      <div class="rpw-status" id="rpwStatus"></div>
      <div class="rpw-result" id="rpwResult"></div>

      <div class="rpw-actions">
        <button class="rpw-btn" id="rpwCancel" type="button">Cancel</button>
        <button class="rpw-btn rpw-btn-primary" id="rpwGenerate" type="button" disabled>Generate Preview</button>
      </div>
    </div>
  `;
  document.body.appendChild(overlay);

  const fileInput = overlay.querySelector('#rpwFileInput');
  const drop = overlay.querySelector('#rpwDrop');
  const dropText = overlay.querySelector('#rpwDropText');
  const generateBtn = overlay.querySelector('#rpwGenerate');
  const statusEl = overlay.querySelector('#rpwStatus');
  const resultEl = overlay.querySelector('#rpwResult');

  function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }

  function openModal() {
    overlay.style.display = 'flex';
  }
  function closeModal() {
    overlay.style.display = 'none';
    fileInput.value = '';
    dropText.textContent = 'Click to upload a room photo';
    drop.classList.remove('has-image');
    generateBtn.disabled = true;
    statusEl.textContent = '';
    statusEl.classList.remove('error');
    resultEl.innerHTML = '';
  }

  trigger.addEventListener('click', openModal);
  overlay.querySelector('.rpw-close').addEventListener('click', closeModal);
  overlay.querySelector('#rpwCancel').addEventListener('click', closeModal);
  overlay.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });

  fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => {
      drop.classList.add('has-image');
      dropText.innerHTML = `<img src="${reader.result}" alt="Room photo preview">`;
      generateBtn.disabled = false;
    };
    reader.readAsDataURL(file);
  });

  generateBtn.addEventListener('click', async () => {
    const file = fileInput.files[0];
    if (!file) return;

    generateBtn.disabled = true;
    statusEl.classList.remove('error');
    statusEl.innerHTML = '<span class="rpw-spinner"></span>Generating your preview... this can take up to 15 seconds.';
    resultEl.innerHTML = '';

    const formData = new FormData();
    formData.append('room_image', file);
    formData.append('product_name', productName);
    formData.append('product_category', productCategory);

    try {
      // Fetch the product image ourselves (the browser can reach localhost
      // fine, unlike a self-referential server-side fetch on a single-
      // threaded local dev server) and upload the actual bytes.
      const productImgResponse = await fetch(productImage);
      if (!productImgResponse.ok) {
        throw new Error('Could not load product image from ' + productImage);
      }
      const productImgBlob = await productImgResponse.blob();
      formData.append('product_image', productImgBlob, 'product.jpg');

      const res = await fetch(ENDPOINT, { method: 'POST', body: formData });
      const data = await res.json();

      if (!data.success) {
        statusEl.textContent = "We couldn't generate a preview: " + data.error;
        statusEl.classList.add('error');
        generateBtn.disabled = false;
        return;
      }

      statusEl.textContent = '';
      const img = document.createElement('img');
      img.src = `data:${data.mime_type};base64,${data.image_base64}`;
      resultEl.appendChild(img);
      generateBtn.disabled = false;
      generateBtn.textContent = 'Try Again';
    } catch (err) {
      statusEl.textContent = 'Request failed: ' + err.message;
      statusEl.classList.add('error');
      generateBtn.disabled = false;
    }
  });
})();