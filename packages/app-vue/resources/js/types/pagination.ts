export type PaginationMode = 'simple' | 'length_aware'

export interface Pagination {
  mode: PaginationMode
  current_page: number
  per_page: number
  from: number | null
  to: number | null
  has_more: boolean
  total: number | null
  last_page: number | null
}
