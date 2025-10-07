<div class="table-responsive">
    <table class="{{ $tableClasses() }} ">
        <tbody>
            @for ($row = 0; $row < $rows; $row++)
                <tr>
                    @for ($col = 0; $col < $columns; $col++)
                        <td class="placeholder-glow">
                            <span class="placeholder col-12"></span>
                        </td>
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
</div>

<style>
    .skeleton-box {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s ease-in-out infinite;
        border-radius: 4px;
    }

    .skeleton-header {
        height: 20px;
        width: 90%;
    }

    .skeleton-text {
        height: 16px;
        width: 90%;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .skeleton-box {
            background: linear-gradient(90deg, #2a2a2a 25%, #1a1a1a 50%, #2a2a2a 75%);
            background-size: 200% 100%;
        }
    }
</style>