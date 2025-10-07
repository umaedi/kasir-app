<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SkeletonTable extends Component
{
    public int $rows;
    public int $columns;
    public bool $striped;
    public bool $bordered;
    public bool $hover;
    public string $size;
    public string $identifier;

    /**
     * Create a new component instance.
     *
     * @param int $rows Jumlah baris skeleton
     * @param int $columns Jumlah kolom skeleton
     * @param bool $striped Tabel striped
     * @param bool $bordered Tabel bordered
     * @param bool $hover Efek hover
     * @param string $size Ukuran tabel (sm, md, lg)
     * @param string $identifier Identifier unik untuk skeleton
     */
    public function __construct(
        int $rows = 5,
        int $columns = 4,
        bool $striped = true,
        bool $bordered = false,
        bool $hover = false,
        string $size = 'md',
        string $identifier = 'default'
    ) {
        $this->rows = $rows;
        $this->columns = $columns;
        $this->striped = $striped;
        $this->bordered = $bordered;
        $this->hover = $hover;
        $this->size = $size;
        $this->identifier = $identifier;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.skeleton-table');
    }

    /**
     * Get table classes.
     *
     * @return string
     */
    public function tableClasses(): string
    {
        $classes = ['table'];

        if ($this->striped) {
            $classes[] = 'table-striped';
        }

        if ($this->bordered) {
            $classes[] = 'table-bordered';
        }

        if ($this->hover) {
            $classes[] = 'table-hover';
        }

        if ($this->size !== 'md') {
            $classes[] = 'table-' . $this->size;
        }

        $classes[] = 'skeleton-' . $this->identifier;

        return implode(' ', $classes);
    }
}