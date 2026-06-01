{{-- Fixed footer band for municipal PDFs (DomPDF). --}}
@page {
    margin: 12mm 12mm 18mm 12mm;
}

.pdf-page-footer {
    position: fixed;
    bottom: -14mm;
    left: 0;
    right: 0;
    height: 12mm;
    border-top: 1px solid #e5e7eb;
    padding-top: 4px;
    font-size: 10px;
    color: #6b7280;
}

.pdf-page-footer__row {
    width: 100%;
    border-collapse: collapse;
}

.pdf-page-footer__row td {
    border: none;
    padding: 0;
    font-size: 10px;
    color: #6b7280;
    vertical-align: middle;
}

.pdf-page-footer__row strong {
    font-weight: 700;
    color: #374151;
}

.pdf-page-footer__left {
    width: 33%;
    text-align: left;
}

.pdf-page-footer__center {
    width: 34%;
    text-align: center;
}

.pdf-page-footer__right {
    width: 33%;
    text-align: right;
}
