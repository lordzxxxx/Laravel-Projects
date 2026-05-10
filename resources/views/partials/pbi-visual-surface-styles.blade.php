{{-- Shared Power BI–style visual chrome (flat greens). Include inside a <style> block. --}}
        .dashboard-card.pbi-visual {
            padding: 0;
            background: transparent;
            border: none;
            box-shadow: none;
            margin-bottom: 18px;
        }
        .pbi-visual {
            padding: 0;
            overflow: hidden;
            border-radius: 14px;
            border: 1px solid rgba(34, 197, 94, 0.22);
            box-shadow: 0 8px 28px rgba(22, 101, 52, 0.07);
            background: #fff;
        }
        .pbi-visual-header {
            background: #166534;
            color: #ffffff;
            padding: 12px 16px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }
        .pbi-visual-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
            color: #ffffff;
        }
        .pbi-visual-title i { color: #bbf7d0; font-size: 1rem; }
        .pbi-visual-meta {
            font-size: 0.7rem;
            color: #ecfdf5;
            text-align: right;
            line-height: 1.35;
        }
        .pbi-visual-meta .pbi-meta-subtle {
            color: rgba(255, 255, 255, 0.78);
            display: inline-block;
            margin-top: 2px;
        }
        .pbi-visual-body {
            background: #fafdfb;
            padding: 14px 14px 10px;
        }
        .pbi-visual-body.pbi-visual-body--flush { padding: 0; }
        .pbi-visual-body .chart-container { position: relative; height: 240px; }
        .owner-pbi-stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
        }
        .owner-pbi-stat-grid .stat-card {
            margin-bottom: 0;
        }
