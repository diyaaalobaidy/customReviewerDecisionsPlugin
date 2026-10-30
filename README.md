# Custom Reviewer Decisions Plugin for Open Journal Systems (OJS)

A comprehensive, production-grade generic plugin for **Open Journal Systems (OJS)** that empowers Journal Managers to create, customize, translate, reorder, and manage **Reviewer Decisions & Recommendations** beyond the rigid built-in OJS defaults.

---

## 🌟 Key Features

- **Full Journal Manager Control**: Configure and edit reviewer decisions directly from the OJS management interface (**Settings > Website > Plugins > Custom Reviewer Decisions Plugin > Settings**).
- **Popular Industry-Standard Decisions Out-of-the-Box**: Curated based on top academic publishers (Elsevier Editorial Manager, Springer Nature, Wiley, IEEE, Taylor & Francis):
  1. **Accept Submission** (Accept as-is)
  2. **Minor Revisions Required** (Textual, bibliographic, or formatting edits; no re-review needed)
  3. **Major Revisions Required** (Structural or methodological overhaul; triggers second review round)
  4. **Resubmit for Review** (Reject & Resubmit for new iteration)
  5. **Resubmit Elsewhere** (Competent work but out of scope; transfer to another journal)
  6. **Decline Submission** (Outright rejection due to fundamental flaws or lack of novelty)
  7. **See Detailed Comments** (Analytical assessment leaving determination to handling editor)
  8. **Conditional Acceptance** (Acceptance subject to data/code verification or compliance checks)
  9. **Reject - Out of Scope** (Submission outside journal aims and focus areas)
- **Multilingual Support (i18n)**:
  - Supports English (`en_US`), Arabic (`ar_IQ`), and every enabled journal locale.
  - Journal Managers can enter localized titles and reviewer criteria per language with tabbed locale switching.
- **Interactive Reviewer Guidelines**:
  - Dynamically displays clear evaluation criteria and instructions below the recommendation dropdown on Step 3 of the review as the reviewer selects options.
- **Visual Color Badges & Dots**:
  - Assign distinct colors (Green, Blue, Orange, Red, Purple, Teal, Dark Red, Gray) to highlight recommendations in editorial grids, review modals, and reviewer reports.
- **One-Click Enable/Disable & Reordering**:
  - Easily disable options that a journal does not want reviewers to select (e.g., disable "Resubmit Elsewhere" or "See Comments").
  - Set custom display sequence in dropdowns.
- **One-Click Reset to Defaults**:
  - Restore the curated default decisions at any time without data loss.
- **Non-Destructive Integration**:
  - Hooks seamlessly into OJS's official `TemplateManager`, `Form`, and grid workflows without breaking core database integrity.

---

## 📂 Directory Structure

```text
customReviewerDecisionsPlugin/
├── index.php                                 # Entrypoint for OJS plugin loader
├── version.xml                               # Plugin version and metadata descriptor
├── CustomReviewerDecisionsPlugin.inc.php     # Main plugin class & hook callbacks
├── classes/
│   └── CustomReviewerDecisionsService.inc.php# Core service for decisions, defaults, & i18n
├── templates/
│   ├── settings/
│   │   ├── settings.tpl                      # Journal Manager management dashboard
│   │   └── decisionEditModal.tpl             # Multilingual modal for adding/editing decisions
│   └── review/
│       └── decisionGuidelines.tpl            # Reviewer step 3 dynamic guidelines container
├── js/
│   └── customReviewerDecisions.js            # Client-side management & reviewer interactive logic
├── css/
│   └── customReviewerDecisions.css           # Modern styles, color badges, and RTL Arabic support
├── locale/
│   ├── en_US/
│   │   └── locale.po                         # English translations
│   └── ar_IQ/
│       └── locale.po                         # Arabic translations
└── README.md                                 # Documentation
```

---

## 🚀 Installation

1. Copy or clone the `customReviewerDecisionsPlugin` directory into your OJS installation under:
   ```bash
   plugins/generic/customReviewerDecisionsPlugin
   ```
2. Navigate to your OJS administration dashboard:
   - Log in as a **Journal Manager** or **Site Administrator**.
   - Go to **Settings > Website > Plugins > Installed Plugins**.
   - Locate **Custom Reviewer Decisions Manager** under Generic Plugins.
   - Check the box to **Enable** the plugin.
3. Click the blue arrow next to the plugin name and select **Settings** to open the management dashboard.

---

## 🛠️ Management Guide for Journal Managers

### 1. Adding a New Custom Decision
1. In the plugin dashboard, click the **Add Custom Decision** button.
2. In the modal:
   - **Internal Key**: Enter a unique identifier (e.g., `fastTrackAccept`).
   - **Display Order**: Enter the sequence number (lower numbers appear first in the dropdown).
   - **Color & Badge**: Select a color (Green, Blue, Orange, Red, Purple, Teal, etc.).
   - **Multilingual Titles & Guidelines**: Switch between the language tabs (e.g., `EN`, `AR`) to type the title and description for each language.
   - **Enable this decision**: Check the box to make it active.
3. Click **Save**. The decision is immediately active and available to peer reviewers!

### 2. Editing an Existing Decision
- Click the **Edit** icon on any row in the decisions table.
- Modify the title in any language, update reviewer guidelines, change color, or adjust sequence.
- Click **Save**.

### 3. Disabling / Enabling a Decision
- Toggle the switch in the **Status** column.
- Disabled decisions will be hidden from the reviewer dropdown on Step 3 and the editor proxy review modal.

### 4. Interactive Reviewer Guidelines
- Under **General Preferences**, keep **Show Interactive Reviewer Guidelines** enabled.
- When peer reviewers evaluate a submission, selecting any decision in the dropdown will display the exact guidance written by the Journal Manager explaining when that decision is appropriate.

---

## 🌍 Supported Locales & Fallbacks

- Every decision supports independent titles and descriptions per locale.
- If a translation is missing for a secondary locale, the system automatically falls back to the journal's primary locale, then English (`en_US`), ensuring no blank labels are ever shown.

---

## 📜 License

Distributed under the **GNU General Public License v3 (GPLv3)**.
