/* See-it-in-your-room modal. Add this script once, then put data-see-in-room on any button.
   <button data-see-in-room data-product-selector="#product-img">See it in your room</button>
   Optional: data-product-src="/img/x.png" (instead of selector), data-endpoint="/api/realistic.php"
   data-placement="wall|floor" (where to drop it), data-size="0.3" (width as fraction of the photo), data-auto-ai (run AI automatically on submit) */
(function () {
  var CSS = ".sir-ov{position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99999;display:flex;align-items:center;justify-content:center;padding:12px;font:15px/1.4 system-ui,sans-serif}" +
  ".sir-ov[hidden]{display:none}.sir-box{background:#fff;color:#222;border-radius:14px;width:min(960px,100%);max-height:100%;overflow:auto;padding:18px;position:relative}" +
  ".sir-box h3{margin:0 0 10px;font-size:18px}.sir-x{position:absolute;top:8px;right:12px;border:0;background:none;font-size:28px;cursor:pointer;color:inherit;line-height:1}" +
  ".sir-btn,.sir-box button.sir-b{display:block;width:100%;margin:8px 0;padding:11px;border:1px solid #d8d2c8;border-radius:8px;background:#fff;color:#222;text-align:center;cursor:pointer;font:inherit}" +
  ".sir-pri{background:#2f5d50!important;color:#fff!important;border-color:#2f5d50!important}.sir-btn:disabled,.sir-b:disabled{opacity:.5}" +
  ".sir-s2{display:grid;grid-template-columns:1fr 250px;gap:14px}@media(max-width:720px){.sir-s2{grid-template-columns:1fr}}" +
  ".sir-stage{background:#f3f0ea;border-radius:10px;padding:6px;display:flex;justify-content:center;align-items:center}" +
  ".sir-stage canvas{max-width:100%;max-height:60vh;touch-action:none;border-radius:6px;cursor:grab}" +
  ".sir-tabs{display:flex;gap:6px;margin-top:8px}.sir-tabs button{padding:6px 12px;border:1px solid #d8d2c8;border-radius:8px;background:#fff;color:#222;cursor:pointer}.sir-tabs .on{background:#2f5d50;color:#fff}" +
  ".sir-ctrl label{display:block;font-size:13px;opacity:.8;margin-top:8px}.sir-ctrl input[type=range]{width:100%}.sir-row{display:flex;gap:8px}.sir-prev{max-width:100%;max-height:40vh;border-radius:8px;display:block;margin:8px auto}.sir-st{font-size:13px;min-height:18px;opacity:.75}";

  var pending = null, opts = { size: .35, placement: "wall", ai: false };
  var modal, cv, ctx, room, prodRaw, prodT, prod, aiImg, view = "edit", endpoint, bgAuto = false;
  var st = defaults();
  var $ = function (s) { return modal.querySelector(s); };

  function build() {
    var s = document.createElement("style"); s.textContent = CSS; document.head.appendChild(s);
    modal = document.createElement("div"); modal.className = "sir-ov"; modal.hidden = true;
    modal.innerHTML =
      '<div class="sir-box" role="dialog" aria-modal="true"><button class="sir-x" aria-label="Close">&times;</button><h3>See it in your room</h3>' +
      '<div class="sir-s1"><p>Upload a photo of your room and we will place the product in it.</p>' +
      '<label class="sir-btn">🖼 Choose room photo<input type="file" accept="image/*" hidden></label>' +
      '<label class="sir-btn">📷 Take a photo<input type="file" accept="image/*" capture="environment" hidden></label>' +
      '<img class="sir-prev" alt="" hidden><button class="sir-b sir-pri sir-submit" disabled>Submit</button></div>' +
      '<div class="sir-s2" hidden><div><div class="sir-stage"><canvas></canvas></div>' +
      '<div class="sir-tabs"><button class="on" data-v="edit">Your placement</button><button data-v="ai" disabled>AI result</button></div></div>' +
      '<div class="sir-ctrl"><div class="sir-st">Drag the product to move it. Pinch to resize and rotate.</div>' +
      '<label>Size</label><input type="range" class="sir-size" min="5" max="150" value="35">' +
      '<label>Rotate</label><input type="range" class="sir-rot" min="-180" max="180" value="0">' +
      '<label>Shadow</label><input type="range" class="sir-sh" min="0" max="100" value="40">' +
      '<label><input type="checkbox" class="sir-bg"> Remove white background</label>' +
      '<div class="sir-row"><button class="sir-b sir-flip">Flip</button><button class="sir-b sir-reset">Reset</button></div>' +
      '<button class="sir-b sir-ai sir-pri">✨ Make it realistic (AI)</button>' +
      '<button class="sir-b sir-dl">Download</button><button class="sir-b sir-new">Use a different photo</button></div></div></div>';
    document.body.appendChild(modal);
    cv = $("canvas"); ctx = cv.getContext("2d");

    $(".sir-x").onclick = close;
    modal.addEventListener("pointerdown", function (e) { if (e.target === modal) close(); });
    document.addEventListener("keydown", function (e) { if (e.key === "Escape" && !modal.hidden) close(); });
    modal.querySelectorAll("input[type=file]").forEach(function (i) { i.onchange = function (e) { var f = e.target.files[0]; e.target.value = ""; if (f) pick(f); }; });
    $(".sir-submit").onclick = function () { if (pending) loadRoom(pending); };
    $(".sir-size").oninput = function (e) { st.w = e.target.value / 100; render(); };
    $(".sir-rot").oninput = function (e) { st.rot = +e.target.value; render(); };
    $(".sir-sh").oninput = render;
    $(".sir-bg").onchange = function (e) { prod = e.target.checked ? prodT : prodRaw; render(); };
    $(".sir-flip").onclick = function () { st.flip = !st.flip; render(); };
    $(".sir-reset").onclick = function () { st = defaults(); $(".sir-size").value = Math.round(st.w * 100); $(".sir-rot").value = 0; render(); };
    $(".sir-new").onclick = function () { $(".sir-s2").hidden = true; $(".sir-s1").hidden = false; };
    $(".sir-dl").onclick = function () { var a = document.createElement("a"); a.href = cv.toDataURL("image/jpeg", .92); a.download = view === "ai" ? "my-room-ai.jpg" : "my-room.jpg"; a.click(); };
    $(".sir-ai").onclick = runAI;
    modal.querySelectorAll(".sir-tabs button").forEach(function (b) { b.onclick = function () { setView(b.dataset.v); }; });
    pointers();
  }

  function defaults() { return { x: .5, y: opts.placement === "floor" ? .72 : .45, w: opts.size, rot: 0, flip: false }; }
  function pick(f) {
    pending = f; var pv = $(".sir-prev"); pv.src = URL.createObjectURL(f); pv.hidden = false; $(".sir-submit").disabled = false;
  }
  function setView(v) {
    view = v; modal.querySelectorAll(".sir-tabs button").forEach(function (b) { b.classList.toggle("on", b.dataset.v === v); });
    cv.style.cursor = v === "edit" ? "grab" : "default"; render();
  }

  function pointers() {
    var P = {}, g = null;
    function norm(e) { var r = cv.getBoundingClientRect(); return { x: (e.clientX - r.left) / r.width, y: (e.clientY - r.top) / r.height, cx: e.clientX, cy: e.clientY }; }
    function anchor() {
      var k = Object.keys(P);
      if (k.length === 1) g = { t: "m", dx: st.x - P[k[0]].x, dy: st.y - P[k[0]].y };
      else if (k.length >= 2) { var a = P[k[0]], b = P[k[1]]; g = { t: "p", d: Math.hypot(b.cx - a.cx, b.cy - a.cy), a: Math.atan2(b.cy - a.cy, b.cx - a.cx), w: st.w, r: st.rot }; }
      else g = null;
    }
    cv.addEventListener("pointerdown", function (e) { if (view !== "edit") return; cv.setPointerCapture(e.pointerId); P[e.pointerId] = norm(e); anchor(); });
    cv.addEventListener("pointermove", function (e) {
      if (!P[e.pointerId] || !g) return; P[e.pointerId] = norm(e); var k = Object.keys(P);
      if (g.t === "m") { st.x = P[k[0]].x + g.dx; st.y = P[k[0]].y + g.dy; }
      else { var a = P[k[0]], b = P[k[1]]; st.w = Math.min(1.5, Math.max(.05, g.w * Math.hypot(b.cx - a.cx, b.cy - a.cy) / g.d));
        st.rot = Math.round(g.r + (Math.atan2(b.cy - a.cy, b.cx - a.cx) - g.a) * 180 / Math.PI); st.rot = ((st.rot + 540) % 360) - 180;
        $(".sir-size").value = Math.round(st.w * 100); $(".sir-rot").value = st.rot; }
      render();
    });
    function up(e) { delete P[e.pointerId]; anchor(); }
    cv.addEventListener("pointerup", up); cv.addEventListener("pointercancel", up);
    cv.addEventListener("wheel", function (e) { if (view !== "edit") return; e.preventDefault(); st.w = Math.min(1.5, Math.max(.05, st.w * (e.deltaY < 0 ? 1.05 : .95))); $(".sir-size").value = Math.round(st.w * 100); render(); }, { passive: false });
  }

  function drawProduct(c, W, H) {
    if (!prod) return;
    var pw = st.w * W, ph = pw * prod.height / prod.width, sh = $(".sir-sh").value / 100;
    c.save(); c.translate(st.x * W, st.y * H); c.rotate(st.rot * Math.PI / 180); if (st.flip) c.scale(-1, 1);
    if (sh > 0) { c.shadowColor = "rgba(0,0,0," + (.55 * sh) + ")"; c.shadowBlur = pw * .06 * (.5 + sh); c.shadowOffsetX = pw * .025; c.shadowOffsetY = pw * .04; }
    c.drawImage(prod, -pw / 2, -ph / 2, pw, ph); c.restore();
  }
  function render() {
    if (!room) return; ctx.clearRect(0, 0, cv.width, cv.height);
    if (view === "ai" && aiImg) { ctx.drawImage(aiImg, 0, 0, cv.width, cv.height); return; }
    ctx.drawImage(room, 0, 0); drawProduct(ctx, cv.width, cv.height);
  }
  function msg(t) { $(".sir-st").textContent = t; }

  function loadRoom(file) {
    var fr = new FileReader();
    fr.onload = function () {
      var img = new Image();
      img.onload = function () {
        var s = Math.min(1, 1600 / Math.max(img.width, img.height)), c = document.createElement("canvas");
        c.width = Math.round(img.width * s); c.height = Math.round(img.height * s); c.getContext("2d").drawImage(img, 0, 0, c.width, c.height);
        room = c; cv.width = c.width; cv.height = c.height; aiImg = null;
        modal.querySelector(".sir-tabs button[data-v=ai]").disabled = true;
        $(".sir-s1").hidden = true; $(".sir-s2").hidden = false; setView("edit");
        msg("Drag the product to move it. Pinch to resize and rotate."); if (opts.ai) runAI();
      };
      img.src = fr.result;
    };
    fr.readAsDataURL(file);
  }

  // Remove near-white background connected to the image edges
  function whiteToAlpha(src) {
    var c = document.createElement("canvas"), s = Math.min(1, 900 / Math.max(src.width, src.height));
    c.width = Math.round(src.width * s); c.height = Math.round(src.height * s);
    var g = c.getContext("2d"); g.drawImage(src, 0, 0, c.width, c.height);
    var d = g.getImageData(0, 0, c.width, c.height), px = d.data, W = c.width, H = c.height, seen = new Uint8Array(W * H), q = [];
    function white(i) { return px[i * 4 + 3] > 0 && px[i * 4] > 235 && px[i * 4 + 1] > 235 && px[i * 4 + 2] > 235; }
    function push(i) { if (!seen[i] && white(i)) { seen[i] = 1; q.push(i); } }
    for (var x = 0; x < W; x++) { push(x); push((H - 1) * W + x); } for (var y = 0; y < H; y++) { push(y * W); push(y * W + W - 1); }
    while (q.length) { var i = q.pop(), xx = i % W; px[i * 4 + 3] = 0; if (xx > 0) push(i - 1); if (xx < W - 1) push(i + 1); if (i >= W) push(i - W); if (i < W * (H - 1)) push(i + W); }
    g.putImageData(d, 0, 0); return c;
  }

  function loadProduct(src, cb) {
    var img = new Image(); img.crossOrigin = "anonymous";
    img.onload = function () {
      try {
        var c = document.createElement("canvas"); c.width = img.naturalWidth; c.height = img.naturalHeight; c.getContext("2d").drawImage(img, 0, 0);
        var p = c.getContext("2d").getImageData(0, 0, 1, 1).data; // throws if image is cross-origin without CORS
        prodRaw = c; prodT = whiteToAlpha(c); bgAuto = p[3] > 0 && p[0] > 235 && p[1] > 235 && p[2] > 235;
        prod = bgAuto ? prodT : prodRaw; $(".sir-bg").checked = bgAuto; cb(true);
      } catch (e) { cb(false); }
    };
    img.onerror = function () { cb(false); }; img.src = src;
  }

  function runAI() {
    var b = $(".sir-ai"); b.disabled = true; msg("Generating… this takes 10–30 seconds.");
    var comp = document.createElement("canvas"); comp.width = room.width; comp.height = room.height;
    var c = comp.getContext("2d"); c.drawImage(room, 0, 0); drawProduct(c, comp.width, comp.height);
    fetch(endpoint, { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ composite: comp.toDataURL("image/jpeg", .9) }) })
      .then(function (r) { return r.json().then(function (j) { if (!r.ok || !j.image) throw new Error(j.error || "Request failed"); return j.image; }); })
      .then(function (url) { var i = new Image(); i.onload = function () { aiImg = i; modal.querySelector(".sir-tabs button[data-v=ai]").disabled = false; setView("ai"); msg("Done. Switch tabs to compare."); }; i.src = url; })
      .catch(function (e) { msg("AI step failed: " + e.message); })
      .then(function () { b.disabled = false; });
  }

  function open(btn) {
    if (!modal) build();
    var sel = btn.getAttribute("data-product-selector"), src = btn.getAttribute("data-product-src");
    if (sel) { var el = document.querySelector(sel); if (el) src = el.currentSrc || el.src; }
    if (!src) { alert("No product image found for this button."); return; }
    endpoint = btn.getAttribute("data-endpoint") || window.SEE_IN_ROOM_ENDPOINT || "/api/realistic.php";
    opts = { size: parseFloat(btn.getAttribute("data-size")) || .35, placement: btn.getAttribute("data-placement") || "wall", ai: btn.hasAttribute("data-auto-ai") };
    room = null; aiImg = null; pending = null; st = defaults();
    $(".sir-prev").hidden = true; $(".sir-submit").disabled = true;
    $(".sir-size").value = Math.round(st.w * 100); $(".sir-rot").value = 0;
    $(".sir-s1").hidden = false; $(".sir-s2").hidden = true; modal.hidden = false; document.body.style.overflow = "hidden";
    loadProduct(src, function (ok) { if (!ok) { modal.hidden = true; document.body.style.overflow = ""; alert("Could not read the product image. It must be on your own domain (or served with CORS enabled)."); } });
  }
  function close() { modal.hidden = true; document.body.style.overflow = ""; }

  document.addEventListener("click", function (e) { var b = e.target.closest("[data-see-in-room]"); if (b) { e.preventDefault(); open(b); } });
})();
