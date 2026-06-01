{{-- Municipal PDF — body layout: spacing, hierarchy, minimal tables. --}}
.pdf-body {
    margin: 0;
    padding: 0;
}

.pdf-section {
    margin: 0 0 18px 0;
    page-break-inside: avoid;
}

.pdf-section__title,
.pdf-section-title {
    margin: 0 0 8px 0;
    padding: 0 0 5px 0;
    border-bottom: 1px solid #e5e7eb;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #457359;
}

.pdf-block__title,
h2.pdf-block__title {
    margin: 0 0 6px 0;
    padding: 0;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #374151;
}

/* KPI / summary strip */
.pdf-metrics,
table.summary {
    width: 100%;
    border-collapse: collapse;
    margin: 0 0 16px 0;
    table-layout: fixed;
}

.pdf-metrics td,
table.summary td {
    width: 25%;
    padding: 10px 8px;
    text-align: center;
    vertical-align: top;
    border: 1px solid #e5e7eb;
    background: #f9fafb;
}

.pdf-metrics td + td,
table.summary td + td {
    border-left: none;
}

.pdf-metric__value,
table.summary .value {
    display: block;
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 700;
    line-height: 1.1;
    color: #111827;
}

.pdf-metric__label,
table.summary .label {
    display: block;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    line-height: 1.25;
    color: #6b7280;
}

/* Two-column body */
.pdf-columns,
table.columns {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.pdf-columns > tbody > tr > td,
table.columns > tbody > tr > td {
    width: 50%;
    padding: 0 12px 0 0;
    vertical-align: top;
    border: none;
}

.pdf-columns > tbody > tr > td + td,
table.columns > tbody > tr > td + td {
    padding: 0 0 0 12px;
}

.pdf-block {
    margin: 0 0 14px 0;
}

/* Data tables */
.pdf-table,
table.pdf-table,
table.columns table,
table.report-layout,
table.report-detail {
    width: 100%;
    border-collapse: collapse;
    margin: 0 0 10px 0;
    font-size: 12px;
}

.pdf-table th,
table.columns table th,
table.report-layout th,
table.report-detail thead th {
    padding: 6px 8px;
    text-align: left;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #4b5563;
    background: #f3f4f6;
    border: none;
    border-bottom: 1px solid #d1d5db;
}

.pdf-table td,
table.columns table td,
table.report-layout td,
table.report-detail tbody td {
    padding: 7px 8px;
    font-size: 12px;
    color: #1f2937;
    border: none;
    border-bottom: 1px solid #e5e7eb;
    background: #ffffff;
    vertical-align: top;
}

.pdf-table tr:last-child td,
table.columns table tr:last-child td,
table.report-detail tbody tr:last-child td {
    border-bottom: none;
}

.pdf-table tbody tr:nth-child(even) td,
table.report-detail tbody tr:nth-child(even) td {
    background: #fafafa;
}

.num,
.number,
td.num,
th.num-col {
    text-align: right;
}

/* Key–value parameter table */
table.report-kv th {
    width: 34%;
    color: #6b7280;
    background: #f9fafb;
    font-weight: 600;
}

table.report-kv td {
    background: #ffffff;
}

/* Metrics row (single line of totals) */
table.report-metrics thead th {
    text-align: center;
    background: #f3f4f6;
    color: #4b5563;
}

table.report-metrics tbody td {
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    color: #111827;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

/* Detail table header — minimal, not heavy green */
table.report-detail thead th {
    background: #f3f4f6;
    color: #374151;
    border-bottom: 1px solid #d1d5db;
}

.tenant-name {
    font-weight: 600;
    color: #111827;
}

.summary-row td {
    font-weight: 700;
    color: #111827;
    background: #f3f4f6 !important;
    border-top: 1px solid #d1d5db !important;
    border-bottom: none !important;
}

table.report-empty td,
td.pdf-empty {
    padding: 16px 8px;
    text-align: center;
    font-size: 11px;
    font-style: italic;
    color: #6b7280;
    border: 1px solid #e5e7eb;
    background: #f9fafb;
}

table.report-layout {
    border: 1px solid #e5e7eb;
}

table.report-layout th,
table.report-layout td {
    border-bottom: 1px solid #e5e7eb;
}

table.report-detail {
    border: 1px solid #e5e7eb;
}

/* Owner tenant KPI row (table-based for DomPDF) */
.pdf-kpi-row {
    width: 100%;
    border-collapse: collapse;
    margin: 0 0 16px 0;
}

.pdf-kpi-row td {
    width: 33.33%;
    padding: 10px 8px;
    text-align: center;
    border: 1px solid #e5e7eb;
    background: #f9fafb;
}

.pdf-kpi-row td + td {
    border-left: none;
}

.pdf-kpi-row--two td {
    width: 50%;
}

.pdf-kpi-row--two td:empty {
    display: none;
}

.pdf-kpi-row .kpi-title {
    margin: 0 0 4px 0;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #6b7280;
}

.pdf-kpi-row .kpi-value {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
}
