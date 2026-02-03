/* GDPR Cookie Consent logic */
(function () {
  const COOKIE_NAME = 'cookie_consent';

  function parseConsentCookie() {
    try {
      const val = document.cookie.split('; ').find((row) => row.startsWith(COOKIE_NAME + '='));
      if (!val) return null;
      return JSON.parse(decodeURIComponent(val.split('=')[1]));
    } catch (e) {
      return null;
    }
  }

  function setVisible(el, show) {
    if (!el) return;
    el.classList[show ? 'remove' : 'add']('hidden');
    if (show) {
      // Ensure banner container is present
      el.style.display = '';
    }
  }

  function loadConsent() {
    const data = parseConsentCookie();
    return data && typeof data === 'object' ? data : null;
  }

  function save(url, body) {
    return window.axios.post(url, body).then((res) => res.data);
  }

  function updateUIFromConsent(consent) {
    const modal = document.getElementById('cc-modal');
    const func = document.getElementById('cc-functional');
    const ana = document.getElementById('cc-analytics');
    const mkt = document.getElementById('cc-marketing');
    if (func) func.checked = !!consent?.functional;
    if (ana) ana.checked = !!consent?.analytics;
    if (mkt) mkt.checked = !!consent?.marketing;
  }

  function shouldShowBanner() {
    return !loadConsent();
  }

  function init() {
    const banner = document.getElementById('cookie-consent');
    const modal = document.getElementById('cc-modal');
    if (!banner) return;

    // Export helpers
    const api = {
      current: () => loadConsent(),
      allows: (key) => !!(loadConsent() && loadConsent()[key]),
      open: () => {
        setVisible(modal, true);
        updateUIFromConsent(loadConsent());
      },
    };
    window.CookieConsent = api;

    if (shouldShowBanner()) {
      setVisible(banner, true);
    }

    const btnAccept = document.getElementById('cc-accept');
    const btnReject = document.getElementById('cc-reject');
    const btnManage = document.getElementById('cc-manage');
    const btnCancel = document.getElementById('cc-cancel');
    const btnSave = document.getElementById('cc-save');

    btnAccept && btnAccept.addEventListener('click', () => {
      save('/consent/accept-all').then(() => {
        setVisible(banner, false);
      });
    });

    btnReject && btnReject.addEventListener('click', () => {
      save('/consent/reject-all').then(() => {
        setVisible(banner, false);
      });
    });

    btnManage && btnManage.addEventListener('click', () => {
      setVisible(modal, true);
      updateUIFromConsent(loadConsent());
    });

    btnCancel && btnCancel.addEventListener('click', () => setVisible(modal, false));

    btnSave && btnSave.addEventListener('click', () => {
      const functional = document.getElementById('cc-functional')?.checked || false;
      const analytics = document.getElementById('cc-analytics')?.checked || false;
      const marketing = document.getElementById('cc-marketing')?.checked || false;
      save('/consent/save', { functional, analytics, marketing }).then(() => {
        setVisible(modal, false);
        setVisible(banner, false);
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
