{{--
    Persian (Jalaali) date picker + attendance helpers.
    Self contained: no external dependency is introduced.
--}}
@push('scripts')
<script>
(() => {
    const PERSIAN_DIGITS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    const WEEKDAY_LABELS = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];
    const MONTH_NAMES = [
        'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
        'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند',
    ];

    const div = (a, b) => ~~(a / b);
    const mod = (a, b) => a - ~~(a / b) * b;

    const BREAKS = [
        -61, 9, 38, 199, 426, 686, 756, 818, 1111, 1181, 1210,
        1635, 2060, 2097, 2192, 2262, 2324, 2394, 2456, 3178,
    ];

    const jalCal = (jy) => {
        const bl = BREAKS.length;
        const gy = jy + 621;
        let leapJ = -14;
        let jp = BREAKS[0];
        let jump = 0;
        let n;

        for (let i = 1; i < bl; i += 1) {
            const jm = BREAKS[i];
            jump = jm - jp;

            if (jy < jm) {
                break;
            }

            leapJ += div(jump, 33) * 8 + div(mod(jump, 33), 4);
            jp = jm;
        }

        n = jy - jp;

        leapJ += div(n, 33) * 8 + div(mod(n, 33) + 3, 4);

        if (mod(jump, 33) === 4 && jump - n === 4) {
            leapJ += 1;
        }

        const leapG = div(gy, 4) - div((div(gy, 100) + 1) * 3, 4) - 150;
        const march = 20 + leapJ - leapG;

        if (jump - n < 6) {
            n = n - jump + div(jump + 4, 33) * 33;
        }

        let leap = mod(mod(n + 1, 33) - 1, 4);

        if (leap === -1) {
            leap = 4;
        }

        return { leap, gy, march };
    };

    const g2d = (gy, gm, gd) => {
        let d = div((gy + div(gm - 8, 6) + 100100) * 1461, 4)
            + div(153 * mod(gm + 9, 12) + 2, 5)
            + gd
            - 34840408;

        d = d - div(div(gy + 100100 + div(gm - 8, 6), 100) * 3, 4) + 752;

        return d;
    };

    const d2g = (jdn) => {
        let j = 4 * jdn + 139361631;
        j = j + div(div(4 * jdn + 183187720, 146097) * 3, 4) * 4 - 3908;

        const i = div(mod(j, 1461), 4) * 5 + 308;
        const gd = div(mod(i, 153), 5) + 1;
        const gm = mod(div(i, 153), 12) + 1;
        const gy = div(j, 1461) - 100100 + div(8 - gm, 6);

        return { gy, gm, gd };
    };

    const j2d = (jy, jm, jd) => {
        const r = jalCal(jy);

        return g2d(r.gy, 3, r.march) + (jm - 1) * 31 - div(jm, 7) * (jm - 7) + jd - 1;
    };

    const toGregorian = (jy, jm, jd) => d2g(j2d(jy, jm, jd));

    const isLeapYear = (jy) => jalCal(jy).leap === 0;

    const monthLength = (jy, jm) => {
        if (jm <= 6) return 31;
        if (jm <= 11) return 30;

        return isLeapYear(jy) ? 30 : 29;
    };

    // Weekday index of the first day of the month (0 = Saturday ... 6 = Friday).
    const firstWeekday = (jy, jm) => {
        const g = toGregorian(jy, jm, 1);
        const day = new Date(g.gy, g.gm - 1, g.gd).getDay();

        return (day + 1) % 7;
    };

    const toPersianDigits = (value) => String(value).replace(/\d/g, (d) => PERSIAN_DIGITS[Number(d)]);

    const parseJalali = (value) => {
        const match = String(value || '').trim().match(/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/);

        if (!match) return null;

        const jy = Number(match[1]);
        const jm = Number(match[2]);
        const jd = Number(match[3]);

        if (jy < 1178 || jy > 3177 || jm < 1 || jm > 12) return null;
        if (jd < 1 || jd > monthLength(jy, jm)) return null;

        return { jy, jm, jd };
    };

    const pad = (n) => String(n).padStart(2, '0');

    document.querySelectorAll('[data-jalali-picker]').forEach((wrapper) => {
        const input = wrapper.querySelector('[data-jalali-datepicker]');
        const calendar = wrapper.querySelector('[data-jalali-calendar]');
        const title = wrapper.querySelector('[data-jalali-title]');
        const weekdays = wrapper.querySelector('[data-jalali-weekdays]');
        const days = wrapper.querySelector('[data-jalali-days]');
        const form = wrapper.closest('form');

        if (!input || !calendar || !title || !days) return;

        // The Jalaali date the server rendered (used as the initial view).
        const fallback = parseJalali(input.value) || { jy: 1400, jm: 1, jd: 1 };

        let viewYear = fallback.jy;
        let viewMonth = fallback.jm;
        let selected = parseJalali(input.value);

        WEEKDAY_LABELS.forEach((label) => {
            const cell = document.createElement('span');
            cell.textContent = label;
            weekdays.appendChild(cell);
        });

        // The server-rendered "today" for the Asia/Tehran timezone.
        const today = parseJalali('{{ $jalaliDate }}') || fallback;

        const render = () => {
            title.textContent = `${MONTH_NAMES[viewMonth - 1]} ${toPersianDigits(viewYear)}`;

            days.innerHTML = '';

            const offset = firstWeekday(viewYear, viewMonth);
            const length = monthLength(viewYear, viewMonth);

            for (let i = 0; i < offset; i += 1) {
                const empty = document.createElement('button');
                empty.type = 'button';
                empty.className = 'jalali-day is-empty';
                empty.tabIndex = -1;
                days.appendChild(empty);
            }

            for (let day = 1; day <= length; day += 1) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'jalali-day';
                button.textContent = toPersianDigits(day);
                button.dataset.day = String(day);

                if (viewYear === today.jy && viewMonth === today.jm && day === today.jd) {
                    button.classList.add('is-today');
                }

                if (selected && viewYear === selected.jy && viewMonth === selected.jm && day === selected.jd) {
                    button.classList.add('is-selected');
                }

                button.addEventListener('click', () => {
                    selected = { jy: viewYear, jm: viewMonth, jd: day };
                    input.value = `${viewYear}/${pad(viewMonth)}/${pad(day)}`;
                    calendar.classList.add('hidden');

                    // Reload the attendance state for the chosen date (never auto-saves).
                    form?.submit();
                });

                days.appendChild(button);
            }
        };

        wrapper.querySelector('[data-jalali-prev]')?.addEventListener('click', () => {
            viewMonth -= 1;

            if (viewMonth < 1) {
                viewMonth = 12;
                viewYear -= 1;
            }

            render();
        });

        wrapper.querySelector('[data-jalali-next]')?.addEventListener('click', () => {
            viewMonth += 1;

            if (viewMonth > 12) {
                viewMonth = 1;
                viewYear += 1;
            }

            render();
        });

        input.addEventListener('focus', () => {
            calendar.classList.remove('hidden');
            const parsed = parseJalali(input.value);

            if (parsed) {
                viewYear = parsed.jy;
                viewMonth = parsed.jm;
                selected = parsed;
            }

            render();
        });

        input.addEventListener('input', () => {
            selected = parseJalali(input.value);
            render();
        });

        document.addEventListener('click', (event) => {
            if (!wrapper.contains(event.target)) {
                calendar.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                calendar.classList.add('hidden');
            }
        });
    });

    // ■ Live absent counter + row highlighting.
    document.querySelectorAll('[data-attendance-form]').forEach((form) => {
        const toggles = Array.from(form.querySelectorAll('[data-absent-toggle]'));
        const counter = form.querySelector('[data-absent-count]');

        const refresh = () => {
            let count = 0;

            toggles.forEach((toggle) => {
                const row = toggle.closest('[data-student-row]');
                const label = toggle.closest('label')?.querySelector('[data-absent-label]');

                row?.classList.toggle('bg-destructive/5', toggle.checked);
                label?.classList.toggle('text-destructive', toggle.checked);
                label?.classList.toggle('text-secondary-foreground', !toggle.checked);

                if (toggle.checked) count += 1;
            });

            if (counter) {
                counter.textContent = toPersianDigits(count);
            }
        };

        toggles.forEach((toggle) => toggle.addEventListener('change', refresh));
        refresh();

        // Confirmation before replacing an already recorded date.
        form.addEventListener('submit', (event) => {
            const message = form.dataset.confirmReplace;

            if (event.submitter && event.submitter.dataset.attendanceSubmit !== undefined) {
                if (message && !window.confirm(message)) {
                    event.preventDefault();

                    return;
                }

                event.submitter.disabled = true;
                event.submitter.classList.add('opacity-70');
                event.submitter.innerHTML = '<i class="ki-filled ki-check"></i> در حال ثبت...';
            }
        });
    });
})();
</script>
@endpush
