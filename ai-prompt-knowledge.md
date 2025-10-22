Component Refactoring Analysis Checklist
1. Component Classification

Questions:
What IS this component?

Atom: Basic UI building block? (Button, Input, Card)

Molecule: Simple group of atoms? (SearchBox, FileItem)

Organism: Complex assembly? (Header, Sidebar, FileExplorer)

Template: Layout structure? (MainLayout, ThreeColumnLayout)

Page: Complete route/view? (DashboardPage, SettingsPage)

Quick Classification Test:

Ask: "Can this be used anywhere without modification?"

✅ YES → Probably an ATOM

❌ NO → Ask: "Does it combine multiple components?"

✅ YES → ORGANISM or TEMPLATE

❌ NO → MOLECULE or SPECIFIC COMPONENT

2. Reusability Analysis

Extraction Opportunities:

Repeated UI patterns – same markup/styling used elsewhere?

Generic functionality – logic that other components could use?

Pure presentation – no business logic, just displays data?

Configurable parts – elements that take props and adapt?

Questions to ask:

"Would 3+ other components benefit from this exact element?"

"Is this tied to specific business logic or domain?"

"Could this work in a different project entirely?"

3. Logic Analysis

What does the logic DO?

State Management – managing reactive data

API Calls – fetching/posting data

Business Rules – domain-specific calculations/validation

UI Behavior – toggles, animations, interactions

Data Transformation – converting/formatting data

Event Handling – responding to user actions

Where should logic live?

Ask: "What type of logic is this?"

Vue Reactivity (ref, computed) → COMPOSABLE (use*)

Pure Functions → UTILS/HELPERS

API Communication → SERVICES (*Api)

Business Rules → SERVICES (*Manager, *Service)

Data Processing → PROCESSORS

Global State → STORE

4. Naming Convention Audit

Does the name match its purpose?

Layout components → End with "Layout"

Page components → End with "Page"

Composables → Start with "use"

Services → End with "Service", "Api", "Manager"

Utils → End with "Utils", "Helpers"

Is the name descriptive?

Avoid generic names (Component.vue, Helper.js)

Include domain if specific (CodemateFileTree.vue)

Indicate complexity level (UserForm vs UserModal vs UserDashboard)

5. Dependencies & Coupling Analysis

What does this import?

External libraries – should these be abstracted?

Business stores – is this tightly coupled to specific domains?

Other components – are these at the right abstraction level?

Utils/helpers – could these be consolidated?

What imports this?

Single component – might be too specific

Multiple components – good candidate for extraction

Different domains – should be in shared location

6. File Location Questions

Current vs Ideal Location:

Ask: "Where would a developer expect to find this?"

Used by 1 feature → /features/[domain]/

Used by multiple features → /shared/ or /components/

Pure UI element → /ui/ or /primitives/

Layout structure → /layouts/

Complete page → /pages/

Business logic → /services/

Domain Specificity:

Feature-specific → /features/[domain]/

Cross-feature → /shared/

App-wide → top-level directories

7. Complexity Assessment

Component Complexity Indicators:

Lines of code – >100 lines = consider splitting

Number of props – >10 props = too complex?

Number of imports – many imports = doing too much?

Nested components – deep nesting = reorganize?

Mixed concerns – UI + business logic = separate?

Simplification Opportunities:

Extract hooks → move reactive logic to composables

Split components → break large components into smaller ones

Create services → move business logic out of components

Add props → make hardcoded values configurable

8. Architecture Red Flags

Anti-patterns to look for:

God components – does everything, knows everything

Prop drilling – passing props through many levels

Mixed abstractions – atoms importing organisms

Business logic in UI – API calls in presentation components

Hardcoded values – should be props or constants

Duplicate code – same logic/markup repeated

Immediate fixes:

Extract constants – move magic numbers/strings

Create composables – move reactive logic

Add prop validation – define expected props

Split concerns – separate UI from business logic

9. Quick Decision Matrix
Component Type	Likely Classification	Location
No imports, pure HTML/CSS	Atom	/ui/atoms/
Combines 2-3 atoms	Molecule	/ui/molecules/
Has business logic, domain-specific	Domain Component	/features/[domain]/
Defines page structure with slots	Layout	/layouts/
Combines many components	Organism	/organisms/
Represents a URL route	Page	/pages/
Contains reactive Vue logic	Composable	/composables/
Makes API calls	Service	/services/
Pure functions only	Utils	/utils/
10. Refactoring Action Items

For each component, create tasks:

Move to correct location – based on classification

Rename appropriately – follow naming conventions

Extract reusable parts – create atoms/molecules

Move logic to appropriate files – composables/services/utils

Update imports – fix all dependent files

Add proper props – make configurable where needed

Document purpose – add comments explaining role

Priority order:

Extract atoms – most reusable, biggest impact

Move business logic – services, composables, utils

Reorganize by domain – group related functionality

Rename for clarity – make names self-documenting

Update file locations – match new organization

Fix import paths – ensure everything still works

Quick Assessment Template

Component: _________________
Current Location: _________________
Classification: [ ] Atom [ ] Molecule [ ] Organism [ ] Template [ ] Page
Reusability: [ ] High [ ] Medium [ ] Low
Domain-specific Logic Type: [ ] UI [ ] Business [ ] API [ ] State [ ] Utils
Should Extract: [ ] UI parts [ ] Logic [ ] Constants [ ] Nothing
Ideal Location: _________________
Rename To: _________________
Action Required: [ ] Move [ ] Rename [ ] Split [ ] Extract Logic [ ] No Change

Component Analysis Prompt

Analyze this component using the refactoring checklist framework:

File to Analyze:
[FILE PATH AND CONTENT HERE]

Analysis Framework:

Component Classification

What IS this component? (Atom/Molecule/Organism/Template/Page)

Evidence supporting classification

Specific type and complexity level

Logic Analysis

What does the logic DO? (State Management, API Calls, Business Rules, UI Behavior, Data Transformation, Event Handling)

Where should this logic live if extracted?

Any business logic mixed with UI?

Reusability Assessment

Can this be used anywhere without modification?

Is it tied to specific business logic or domain?

Would 3+ other components benefit from this?

Generic functionality vs domain-specific?

Naming Convention Check

Does the name match its purpose and follow conventions?

Should it end with Layout/Page/Service/etc?

Is the name descriptive and clear?

Location Analysis

Current vs ideal location

Feature-specific vs shared/cross-feature

Where would a developer expect to find this?

Response Format:

Clear section headers (## Component Analysis)

Checkboxes (✅/❌) for yes/no assessments

Evidence bullets supporting classifications

Quick summary box at the end: type, complexity, reusability, logic type, naming status, location status, recommendations

Final Recommendation:

Should be moved to: [location]

Should be renamed to: [name]

Logic should be extracted to: [file type]

Classification summary with reasoning

Usage: Copy this prompt and replace [FILE PATH AND CONTENT HERE] with the actual file you want analyzed. The AI will provide a structured analysis following the same format.

✅ This version is now spaced, indented, and visually scannable, making it much easier to follow and use.

If you want, I can also turn it into a compact visual table/checklist PDF version so it’s easier to print and mark off while refactoring.

Do you want me to do that next?

Component Analysis Prompt        

  Analyze this component using     
  the refactoring checklist        
  framework:

  File to Analyze:

  [FILE PATH AND CONTENT HERE]     

  Analysis Framework:

  Please provide a structured      
  analysis covering:

  1. Component Classification      

  - What IS this component?        
  (Atom/Molecule/Organism/Templ    
  ate/Page)
  - Evidence supporting the        
  classification
  - Specific type and
  complexity level

  2. Logic Analysis

  - What does the logic DO?        
  (Check all that apply: State     
  Management, API Calls,
  Business Rules, UI Behavior,     
  Data Transformation, Event       
  Handling)
  - Where should this logic        
  live if extracted?
  - Any business logic mixed       
  with UI?

  3. Reusability Assessment        

  - Can this be used anywhere      
  without modification?
  - Is it tied to specific
  business logic or domain?        
  - Would 3+ other components      
  benefit from this?
  - Generic functionality vs       
  domain-specific?

  4. Naming Convention Check       

  - Does the name match its        
  purpose and follow
  conventions?
  - Should it end with
  Layout/Page/Service/etc?
  - Is the name descriptive and    
   clear?

  5. Location Analysis

  - Current vs ideal location      
  - Feature-specific vs
  shared/cross-feature
  - Where would a developer        
  expect to find this?

  Response Format:

  Structure your response with:    
  - Clear section headers (##      
  Component Analysis)
  - Checkboxes (✅/❌) for
  yes/no assessments
  - Evidence bullets supporting    
   classifications
  - Quick summary box at the       
  end with:
    - Type, Complexity,
  Reusability level
    - Logic type, Naming
  status, Location status
    - Specific recommendations     

  Final Recommendation:

  Provide actionable next
  steps:
  - Should be moved to:
  [location]
  - Should be renamed to:
  [name]
  - Logic should be extracted      
  to: [file type]
  - Classification summary with    
   reasoning

  Focus on being practical and     
  specific rather than 
  theoretical. Give clear 
  actionable recommendations I     
  can immediately implement.       

  ---
  Usage: Copy this prompt and      
  replace [FILE PATH AND
  CONTENT HERE] with the actual    
   file you want analyzed. The     
  AI will provide a structured     
  analysis following the same      
  format as the MainLayout.vue     
  analysis.
