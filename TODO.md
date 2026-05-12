## TODO - Cakes Advanced Search/Filters + Performance + Seed Data

### Backend (API + performance)

- [x] Add server-side pagination + advanced search/filter/sort to **Admin Products** API (controller + routes if needed)
- [x] Add server-side pagination + advanced search/filter/sort to **Admin Reports** listing (current ReportController@index is legacy full get)
- [x] Add server-side pagination + filters/sort/search to **Baker**:
    - [x] production orders endpoint (schedule filtering/urgent ordering)
    - [x] production schedule endpoint (nearest deadlines)
    - [x] completed orders endpoint
- [x] Add UI filters/sort/search + pagination controls for Baker pages
- [ ] Add server-side pagination + filters/sort/search to **Customer Service**:
    - [x] incoming orders endpoint
    - [x] payments endpoint
    - [x] pickup schedule endpoint
    - [x] history endpoint
- [ ] Performance: enforce select-only columns and correct eager loading (avoid N+1)
- [ ] Performance: add caching for frequently accessed dashboard statistics (admin summary, revenue by day)
- [ ] Performance: minimize repeated API calls (frontend loading states + avoid double fetch)
- [ ] Prevent unnecessary full-table queries (replace get()/limit(200) where list pages exist)

### Frontend (UX)

- [ ] Add/align debounced search and loading states on missing pages
- [ ] Add skeleton loaders and responsive filter UI for list pages that currently lack it
- [ ] Ensure all list pages use server-side pagination meta only
- [ ] Add lazy image loading in product/order cards where applicable

### Seeders & demo data

- [x] Create ProductSeeder:
    - [x] 10 products total (5 customizable, 5 non-customizable)
    - [x] Customizable products include flavors, sizes, toppings, decorations, custom text, and additional customization prices
    - [x] Non-customizable products have fixed prices and no customization linkage
- [x] Create demo customer account seeder:
    - [x] Name: Tira
    - [x] Email: customer@cakes.com
    - [x] Password: password
- [x] Create cart demo seeder:
    - [x] Active cart for demo customer
    - [x] Multiple cart items (>=1 customizable + >=1 non-customizable)
    - [x] Include selected customizations + uploaded design simulation
- [x] Create order demo seeder:
    - [x] Completed, Pending, In-process orders
    - [x] Include order items + customization details + pickup dates + statuses
- [x] Create payment demo seeder:
    - [x] DP and Full payment examples
    - [x] Include paid/unpaid statuses
- [x] Create review demo seeder:
    - [x] Product reviews from demo customer
    - [x] Ratings and comments

### Verification

- [x] Run tests (`php artisan test`) PASS
- [x] Run migrations and seeders
- [ ] Manually verify each dashboard page loads fast and filters/pagination work

### Backend pagination/filter progress

- [x] Baker production orders endpoint now supports search/filter/sort + server-side pagination
- [x] Baker schedule endpoint now supports filters + server-side pagination
- [x] Baker frontend pages adjusted to consume paginated responses (`res.data.data`)
- [ ] Customer Service (incoming/history/payments/pickup) pagination/filter/sort still pending

## APPEND ONLY - NEW TODO EXTENSIONS (Customer Service + Dashboard UX + Logout + Performance)

### Admin Dashboard Search & Filter Improvements (APPEND ONLY)

#### Products Page

- [ ] Add advanced search by: product name, category
- [ ] Add filters: customizable products, non-customizable products, product status, price range
- [ ] Add sorts: newest, oldest, lowest price, highest price
- [ ] Ensure server-side pagination is used for Products list
- [ ] Display badges: Customizable / Non-Customizable / Categories

#### Categories Page

- [ ] Add search by category name
- [ ] Add sort: newest/oldest
- [ ] Ensure server-side pagination

#### Cake Customizations Page

- [ ] Add search by: customization name, option name
- [ ] Add filters: customization type, products using the customization
- [ ] Add sort: newest/oldest
- [ ] Ensure server-side pagination
- [ ] Support examples: Flavor, Size, Toppings, Decorations, Custom Text

#### Orders Page

- [ ] Add search by: order ID, customer name
- [ ] Add filters: order status, payment status, pickup date
- [ ] Add sort: newest/oldest
- [ ] Ensure server-side pagination

#### Payments Page

- [ ] Add search by: customer name, order ID
- [ ] Add filters: DP payment, Full payment, Paid, Unpaid
- [ ] Add sort: payment date
- [ ] Ensure server-side pagination

### Customer Service Dashboard Search & Filter Features (APPEND ONLY)

#### Incoming Orders Page

- [ ] Add search by: order ID, customer name
- [ ] Add filters: order status, pickup date, payment status
- [ ] Add sort: newest/oldest
- [ ] Ensure server-side pagination

#### Payments Page

- [ ] Add payment data rendering improvements
- [ ] Add filter by: payment type, payment status
- [ ] Add sort: latest payment
- [ ] Ensure server-side pagination

#### Pickup Schedule Page

- [ ] Add search customer/order
- [ ] Add filters: pickup date, order completion
- [ ] Add sort: nearest pickup date
- [ ] Ensure server-side pagination

#### Order History Page

- [ ] Add search by order/customer
- [ ] Add filters: completed, cancelled, pending
- [ ] Add sort: newest/oldest
- [ ] Ensure server-side pagination

### Baker Dashboard Search & Filter Features (APPEND ONLY)

#### Production Orders Page

- [ ] Enhance with: urgent order highlighting, better deadline sorting, loading states
- [ ] Add debounce optimization for search

#### Production Schedule Page

- [ ] Enhance with: responsive filters, nearest-deadline prioritization, improved pagination UX

#### Completed Orders Page

- [ ] Add search completed orders
- [ ] Add filter by completion date
- [ ] Add sort newest/oldest
- [ ] Ensure server-side pagination

### Sidebar Logout Improvement (APPEND ONLY)

- [ ] Add Logout button fixed at bottom of the sidebar for:
    - [ ] Admin Dashboard
    - [ ] Customer Service Dashboard
    - [ ] Baker Dashboard
- [ ] Requirements: responsive design, logout icon, confirmation modal, clear session/token, redirect to login

### ⚡ PERFORMANCE OPTIMIZATION CONTINUATION (APPEND ONLY)

- [ ] Continue unfinished optimization tasks:
    - [ ] enforce select-only columns
    - [ ] improve eager loading
    - [ ] avoid N+1 queries
    - [ ] add dashboard caching
    - [ ] minimize repeated API calls
    - [ ] add debounced search
    - [ ] add skeleton loaders
    - [ ] ensure all list pages use server-side pagination only
    - [ ] add lazy image loading
