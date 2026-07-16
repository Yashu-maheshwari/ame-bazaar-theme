# Business Acceptance Rules

**Never say PASS because code compiled.**
**Never say PASS because git push succeeded.**
**Never say PASS because REST API returned success.**

PASS is allowed ONLY after:
1. Live website checked.
2. WordPress dashboard checked.
3. Feature works exactly as business owner requested.
4. Business Acceptance Test completed.

If any one fails, Status = FAIL.
Continue debugging until production works.
