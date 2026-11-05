const qs = (selector, root = document) => root.querySelector(selector);
const qsa = (selector, root = document) => Array.from(root.querySelectorAll(selector));
const basePath = (document.documentElement.dataset.basePath || '').replace(/\/$/, '');
const siteUrl = (path) => {
    if (/^(?:https?:)?\/\//i.test(path) || /^(?:mailto:|tel:|#)/i.test(path))
        return path;
    return `${basePath}/${path.replace(/^\/+/, '')}`;
};
document.documentElement.classList.add('js');
// Sticky header state.
const header = qs('[data-site-header]');
const syncHeader = () => { header?.classList.toggle('is-scrolled', window.scrollY > 12); };
syncHeader();
window.addEventListener('scroll', syncHeader, { passive: true });
// Mobile navigation. The runtime attribute is data-menu-toggle (not the legacy data-mobile-toggle).
const mobileButton = qs('[data-menu-toggle]');
const mobilePanel = qs('[data-mobile-panel]');
let savedScrollY = 0;
let menuOpen = false;
const setMobile = (open) => {
    if (!mobileButton || !mobilePanel)
        return;
    const wasOpen = menuOpen;
    if (open && !wasOpen)
        savedScrollY = window.scrollY;
    menuOpen = open;
    mobileButton.setAttribute('aria-expanded', String(open));
    mobileButton.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    mobilePanel.hidden = !open;
    document.documentElement.classList.toggle('menu-open', open);
    document.body.classList.toggle('menu-open', open);
    if (!open && wasOpen && Math.abs(window.scrollY - savedScrollY) > 2 && window.innerWidth <= 1024) {
        window.scrollTo({ top: savedScrollY, left: 0, behavior: 'auto' });
    }
};
mobileButton?.addEventListener('click', () => setMobile(mobileButton.getAttribute('aria-expanded') !== 'true'));
qsa('[data-mobile-panel] a').forEach((link) => link.addEventListener('click', () => setMobile(false)));
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape')
        setMobile(false);
});
window.addEventListener('resize', () => {
    if (window.innerWidth > 1024 && menuOpen)
        setMobile(false);
}, { passive: true });
window.addEventListener('pageshow', () => setMobile(false));
// Make hash navigation reliable below the sticky header and after late-loading fonts/images.
const scrollToHash = (hash, replace = false) => {
    if (!hash || hash === '#')
        return;
    let target = null;
    try {
        target = document.querySelector(hash);
    }
    catch {
        return;
    }
    if (!target)
        return;
    target.scrollIntoView({ block: 'start', behavior: 'smooth' });
    if (replace)
        history.replaceState(null, '', hash);
};
qsa('a[href^="#"]').forEach((link) => {
    link.addEventListener('click', (event) => {
        const hash = link.getAttribute('href') || '';
        if (hash === '#') {
            event.preventDefault();
            return;
        }
        const target = qs(hash);
        if (!target)
            return;
        event.preventDefault();
        setMobile(false);
        scrollToHash(hash);
        history.pushState(null, '', hash);
    });
});
if (location.hash) {
    window.addEventListener('load', () => window.setTimeout(() => scrollToHash(location.hash, true), 80), { once: true });
}
// Chatbot panel.
const chatbot = qs('[data-chatbot-panel]');
qs('[data-chatbot-toggle]')?.addEventListener('click', () => {
    if (!chatbot)
        return;
    chatbot.hidden = !chatbot.hidden;
});
qs('[data-chatbot-close]')?.addEventListener('click', () => {
    if (chatbot)
        chatbot.hidden = true;
});
// Achievement filters (template uses data-filter; legacy builds used data-award-filter).
qsa('[data-filter], [data-award-filter]').forEach((button) => {
    button.addEventListener('click', () => {
        const campus = button.dataset.filter || button.dataset.awardFilter || 'all';
        qsa('[data-filter], [data-award-filter]').forEach((item) => {
            item.classList.toggle('active', item === button);
            item.setAttribute('aria-pressed', String(item === button));
        });
        qsa('[data-campus]').forEach((card) => {
            card.hidden = campus !== 'all' && card.dataset.campus !== campus;
        });
    });
});
// Admissions FAQ accordion.
qsa('.faq-item').forEach((item, index) => {
    const button = qs('button', item);
    const answer = qs('.answer', item);
    if (!button || !answer)
        return;
    const id = answer.id || `faq-answer-${index + 1}`;
    answer.id = id;
    button.setAttribute('aria-controls', id);
    button.setAttribute('aria-expanded', 'false');
    button.addEventListener('click', () => {
        const open = !item.classList.contains('open');
        item.classList.toggle('open', open);
        button.setAttribute('aria-expanded', String(open));
        const marker = button.lastElementChild;
        if (marker)
            marker.textContent = open ? '−' : '+';
    });
});
// Indicative admissions age/grade helper.
const eligibilityForm = qs('[data-eligibility-form]');
eligibilityForm?.addEventListener('submit', (event) => {
    event.preventDefault();
    const input = qs('input[type="date"]', eligibilityForm);
    const gradeNode = qs('[data-grade]');
    const ageNode = qs('[data-age]');
    if (!input?.value || !gradeNode || !ageNode)
        return;
    const dob = new Date(`${input.value}T00:00:00`);
    const cutoff = new Date('2026-03-31T00:00:00');
    if (Number.isNaN(dob.getTime()) || dob > cutoff) {
        gradeNode.textContent = 'Check date of birth';
        ageNode.textContent = 'Invalid date';
        return;
    }
    let years = cutoff.getFullYear() - dob.getFullYear();
    let months = cutoff.getMonth() - dob.getMonth();
    if (cutoff.getDate() < dob.getDate())
        months -= 1;
    if (months < 0) {
        years -= 1;
        months += 12;
    }
    ageNode.textContent = `${years} year${years === 1 ? '' : 's'} ${months} month${months === 1 ? '' : 's'}`;
    let grade = 'Please contact admissions';
    if (years === 3)
        grade = 'Nursery';
    else if (years === 4)
        grade = 'Junior KG';
    else if (years === 5)
        grade = 'Senior KG';
    else if (years >= 6 && years <= 16)
        grade = years === 6 ? 'Grade I' : `Grade ${toRoman(years - 5)}`;
    else if (years < 3)
        grade = 'Not yet age-eligible';
    gradeNode.textContent = grade;
});
function toRoman(value) {
    const map = [[10, 'X'], [9, 'IX'], [5, 'V'], [4, 'IV'], [1, 'I']];
    let out = '';
    let n = value;
    for (const [amount, symbol] of map) {
        while (n >= amount) {
            out += symbol;
            n -= amount;
        }
    }
    return out;
}
// Enquiry forms.
qsa('form[data-enquiry-form]').forEach((form) => form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const status = qs('[data-form-status]', form);
    const submit = qs('button[type="submit"]', form);
    const data = Object.fromEntries(new FormData(form).entries());
    if (submit)
        submit.disabled = true;
    if (status)
        status.textContent = 'Submitting…';
    try {
        const response = await fetch(siteUrl('/api/enquiry'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data)
        });
        const type = response.headers.get('content-type') || '';
        const json = type.includes('application/json') ? await response.json() : { ok: false, error: 'Unexpected server response.' };
        if (json.ok) {
            const formType = String(data.form_type || 'enquiry');
            if (status)
                status.textContent = formType === 'campus_visit'
                    ? 'Thank you. Your visit request has been received; the campus team will contact you to confirm availability.'
                    : 'Thank you. The relevant Lexicon team will contact you.';
            form.reset();
        }
        else if (status) {
            status.textContent = json.error || 'Please check the form.';
        }
    }
    catch {
        if (status)
            status.textContent = 'Unable to submit right now. Please try again.';
    }
    finally {
        if (submit)
            submit.disabled = false;
    }
}));
// Campus ribbon: keep the active campus item visible on tablet/mobile swipe rails.
const syncCampusRail = () => {
    const rail = qs('[data-campus-swipe]');
    if (!rail || window.innerWidth > 1024)
        return;
    const active = qs('a.active', rail);
    if (!active)
        return;
    const targetLeft = Math.max(0, active.offsetLeft - (rail.clientWidth - active.clientWidth) / 2);
    rail.scrollTo({ left: targetLeft, behavior: 'auto' });
};
const closeCampusSwitcher = (event) => {
    const target = event.target;
    qsa('.campus-switcher[open]').forEach((details) => {
        if (target && details.contains(target))
            return;
        details.open = false;
    });
};
window.addEventListener('load', syncCampusRail, { once: true });
window.addEventListener('resize', syncCampusRail, { passive: true });
document.addEventListener('click', closeCampusSwitcher);
qsa('[data-campus-swipe] a').forEach((link) => {
    link.addEventListener('focus', () => {
        if (window.innerWidth <= 1024)
            link.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
    });
});
// Transport Route Finder — search campus route/stop data without exposing operational vehicle/time data.
const transportSearch = qs('[data-transport-search]');
const transportCampusFilter = qs('[data-transport-campus-filter]');
const transportRouteFilter = qs('[data-transport-route-filter]');
const transportCount = qs('[data-transport-count]');
const transportEmpty = qs('[data-transport-empty]');
const normaliseTransportText = (value) => value
    .normalize('NFKD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, ' ')
    .trim();
const syncTransportFinder = () => {
    if (!transportSearch && !transportRouteFilter && !transportCampusFilter)
        return;
    const query = normaliseTransportText(transportSearch?.value || '');
    const campusValue = transportCampusFilter?.value || 'all';
    const routeValue = transportRouteFilter?.value || 'all';
    let visibleStops = 0;
    let visibleRoutes = 0;
    qsa('[data-transport-route]').forEach((route) => {
        const campus = route.dataset.campus || '';
        const routeName = route.dataset.route || '';
        const key = `${campus}||${routeName}`;
        const campusMatch = campusValue === 'all' || campusValue === campus;
        const routeMatch = routeValue === 'all' || routeValue === key;
        const rows = qsa('.transport-table-wrap [data-transport-stop]', route);
        const cards = qsa('.transport-stop-cards [data-transport-stop]', route);
        let routeStopMatches = 0;
        rows.forEach((row, index) => {
            const searchable = normaliseTransportText(row.dataset.search || '');
            const match = campusMatch && routeMatch && (query === '' || searchable.includes(query));
            row.hidden = !match;
            const card = cards[index];
            if (card)
                card.hidden = !match;
            if (match)
                routeStopMatches += 1;
        });
        const routeVisible = campusMatch && routeMatch && routeStopMatches > 0;
        route.hidden = !routeVisible;
        if (routeVisible) {
            visibleRoutes += 1;
            visibleStops += routeStopMatches;
            if (query.length >= 2)
                route.open = true;
        }
    });
    qsa('[data-transport-campus]').forEach((group) => {
        const routes = qsa('[data-transport-route]', group);
        group.hidden = routes.every((route) => route.hidden);
    });
    if (transportCount)
        transportCount.textContent = String(visibleStops);
    if (transportEmpty)
        transportEmpty.hidden = visibleRoutes !== 0;
    if (transportCampusFilter && transportRouteFilter) {
        Array.from(transportRouteFilter.options).forEach((option) => {
            if (option.value === 'all') {
                option.hidden = false;
                return;
            }
            const optionCampus = option.value.split('||', 1)[0];
            option.hidden = campusValue !== 'all' && optionCampus !== campusValue;
        });
        if (transportRouteFilter.selectedOptions[0]?.hidden)
            transportRouteFilter.value = 'all';
    }
};
transportSearch?.addEventListener('input', syncTransportFinder);
transportCampusFilter?.addEventListener('change', () => {
    if (transportRouteFilter)
        transportRouteFilter.value = 'all';
    syncTransportFinder();
});
transportRouteFilter?.addEventListener('change', syncTransportFinder);
syncTransportFinder();
export { basePath, siteUrl };
// v0.4.0 — Campus Academics: family lens, planner filters and assessment tabs.
const familyButtons = qsa('[data-family-filter]');
const familyEvents = qsa('[data-family-event]');
const FAMILY_KEY = 'lexicon-family-band';
const applyFamilyBand = (band) => {
    familyButtons.forEach((button) => {
        const active = (button.dataset.familyFilter || 'all') === band;
        button.classList.toggle('active', active);
        button.setAttribute('aria-pressed', String(active));
    });
    familyEvents.forEach((event) => {
        const audience = (event.dataset.audience || 'all').split(/\s+/);
        event.hidden = band !== 'all' && !audience.includes('all') && !audience.includes(band);
    });
    try {
        localStorage.setItem(FAMILY_KEY, band);
    }
    catch { /* storage can be unavailable */ }
};
if (familyButtons.length) {
    let saved = 'all';
    try {
        saved = localStorage.getItem(FAMILY_KEY) || 'all';
    }
    catch { /* ignore */ }
    if (!familyButtons.some((button) => button.dataset.familyFilter === saved))
        saved = 'all';
    applyFamilyBand(saved);
    familyButtons.forEach((button) => button.addEventListener('click', () => applyFamilyBand(button.dataset.familyFilter || 'all')));
}
const academicMonthSelect = qs('[data-academic-month-select]');
const academicCategorySelect = qs('[data-academic-category-select]');
const academicAudienceSelect = qs('[data-academic-audience-select]');
const academicEmpty = qs('[data-academic-filter-empty]');
const academicPanels = qsa('[data-academic-month-panel]');
const syncAcademicPlanner = () => {
    if (!academicMonthSelect || !academicPanels.length)
        return;
    const month = academicMonthSelect.value;
    const category = academicCategorySelect?.value || 'all';
    const audience = academicAudienceSelect?.value || 'all';
    let anyVisible = false;
    academicPanels.forEach((panel) => {
        const panelActive = panel.dataset.academicMonthPanel === month;
        panel.hidden = !panelActive;
        if (!panelActive)
            return;
        qsa('[data-academic-event]', panel).forEach((event) => {
            const eventCategory = event.dataset.category || 'event';
            const eventAudience = (event.dataset.audience || 'all').split(/\s+/);
            const categoryMatch = category === 'all' || eventCategory === category;
            const audienceMatch = audience === 'all' || eventAudience.includes('all') || eventAudience.includes(audience);
            event.hidden = !(categoryMatch && audienceMatch);
            if (!event.hidden)
                anyVisible = true;
        });
    });
    if (academicEmpty)
        academicEmpty.hidden = anyVisible;
    const url = new URL(window.location.href);
    url.searchParams.set('month', month);
    history.replaceState(null, '', `${url.pathname}${url.search}${url.hash}`);
};
if (academicMonthSelect) {
    const requested = new URLSearchParams(location.search).get('month');
    if (requested && Array.from(academicMonthSelect.options).some((option) => option.value === requested))
        academicMonthSelect.value = requested;
    academicMonthSelect.addEventListener('change', syncAcademicPlanner);
    academicCategorySelect?.addEventListener('change', syncAcademicPlanner);
    academicAudienceSelect?.addEventListener('change', syncAcademicPlanner);
    syncAcademicPlanner();
}
qsa('[data-assessment-tab]').forEach((button) => {
    button.addEventListener('click', () => {
        const key = button.dataset.assessmentTab || '';
        qsa('[data-assessment-tab]').forEach((tab) => {
            const active = tab === button;
            tab.classList.toggle('active', active);
            tab.setAttribute('aria-pressed', String(active));
        });
        qsa('[data-assessment-panel]').forEach((panel) => {
            panel.hidden = panel.dataset.assessmentPanel !== key;
        });
    });
});
