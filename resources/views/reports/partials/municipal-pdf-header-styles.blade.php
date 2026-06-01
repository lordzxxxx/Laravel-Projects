{{-- Municipal PDF — header typography & layout (DomPDF: DejaVu Sans). --}}
body {
    font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
    font-size: 12px;
    line-height: 1.45;
    color: #1f2937;
}

.header {
    margin: 0 0 2px 0;
    padding: 0;
    border: none;
}

.header-table {
    width: auto;
    margin: 0 auto;
    border-collapse: collapse;
    table-layout: fixed;
}

.header-table td {
    border: none;
    vertical-align: middle;
    padding: 0;
}

/* Fixed 50px spacer — keeps logos beside text (DomPDF won't stretch them to page edges). */
.header-gap {
    width: 50px;
    min-width: 50px;
    max-width: 50px;
    padding: 0;
    font-size: 1px;
    line-height: 1px;
}

.header-logo-cell {
    width: 100px;
    white-space: nowrap;
    vertical-align: middle;
}

.header-logo-cell--left {
    text-align: right;
}

.header-logo-cell--right {
    text-align: left;
}

.header-center {
    text-align: center;
    padding: 0;
    white-space: nowrap;
    vertical-align: middle;
}

.header-logo-slot {
    display: inline-block;
    width: 100px;
    height: 100px;
    line-height: 100px;
    overflow: hidden;
    vertical-align: middle;
}

.header-logo-cell .header-logo-slot {
    margin: 0;
}

.header-side-logo {
    display: inline-block;
    vertical-align: middle;
    border: none;
}

/* Tall Love mark vs round LGU seal — matched for equal visual weight */
.header-side-logo--love {
    width: 92px;
    height: 100px;
}

.header-side-logo--lgu {
    width: 94px;
    height: 94px;
}

.header-topline,
.header-main,
.header-office,
.header-report-line,
.header h1,
.header p {
    font-size: 12px;
    color: #000000;
}

.header-topline {
    margin: 0 0 4px 0;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.header-main {
    margin: 0 0 3px 0;
    font-weight: 700;
    line-height: 1.2;
}

.header-office {
    margin: 0 0 6px 0;
    font-weight: 600;
}

.header-report-line {
    margin: 0;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.header h1 {
    margin: 6px 0 0 0;
    font-weight: 700;
}

.header p {
    margin: 2px 0 0 0;
    font-weight: 400;
}

.header-divider {
    width: 100%;
    height: 8px;
    margin: 10px 0 12px 0;
    background-repeat: repeat-x;
    background-position: center center;
    background-size: auto 8px;
}
